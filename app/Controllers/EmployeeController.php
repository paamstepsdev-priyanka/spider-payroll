<?php

namespace App\Controllers;

use App\Models\ContractorModel;
use App\Models\EmployeeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class EmployeeController extends BaseController
{
    protected EmployeeModel $employeeModel;
    protected ContractorModel $contractorModel;

    public function __construct()
    {
        $this->employeeModel   = new EmployeeModel();
        $this->contractorModel = new ContractorModel();
    }

    /**
     * Display list of employees with search & filter parameters
     */
    public function index()
    {
        $search       = trim((string) $this->request->getGet('search'));
        $contractorId = $this->request->getGet('contractor_id') ? (int) $this->request->getGet('contractor_id') : null;
        $status       = trim((string) $this->request->getGet('status'));
        $perPage      = 10;

        $employees   = $this->employeeModel->getFilteredEmployees($search, $contractorId, $status, $perPage);
        $contractors = $this->contractorModel->orderBy('contractor_name', 'ASC')->findAll();

        $data = [
            'title'        => 'Employees - Spider Payroll',
            'employees'    => $employees,
            'contractors'  => $contractors,
            'pager'        => $this->employeeModel->pager,
            'search'       => $search,
            'contractorId' => $contractorId,
            'status'       => $status,
        ];

        return view('employees/index', $data);
    }

    /**
     * Show form to create a new employee
     */
    public function create()
    {
        // Only active contractors should be selectable when creating a new employee
        $contractors = $this->contractorModel->where('status', 'active')
            ->orderBy('contractor_name', 'ASC')
            ->findAll();

        $data = [
            'title'       => 'Add Employee - Spider Payroll',
            'contractors' => $contractors,
        ];

        return view('employees/create', $data);
    }

    /**
     * Store a newly created employee in the database
     */
    public function store()
    {
        $rules = [
            'employee_name'       => 'required|max_length[150]',
            'contractor_id'       => 'permit_empty|is_natural_no_zero|is_not_unique[contractors.contractor_id]',
            'biometric_code'      => 'permit_empty|max_length[50]|is_unique[employees.biometric_code]',
            'phone_number'        => 'permit_empty|max_length[20]|is_unique[employees.phone_number]',
            'gender'              => 'required|in_list[male,female,other]',
            'date_of_birth'       => 'required|valid_date',
            'date_of_joining'     => 'required|valid_date',
            'date_of_leaving'     => 'permit_empty|valid_date',
            'exit_reason'         => 'permit_empty|max_length[255]',
            'designation'         => 'permit_empty|max_length[100]',
            'department'          => 'permit_empty|max_length[100]',
            'monthly_base_salary' => 'required|numeric|greater_than_equal_to[0]',
            'bank_name'           => 'permit_empty|max_length[100]',
            'bank_account_number' => 'permit_empty|max_length[50]|is_unique[employees.bank_account_number]',
            'ifsc_code'           => 'permit_empty|max_length[20]',
            'pan_number'          => 'permit_empty|max_length[20]',
            'aadhaar_number'      => 'permit_empty|max_length[20]',
            'status'              => 'required|in_list[active,relieved,inactive]',
        ];

        $messages = [
            'employee_name' => [
                'required'   => 'Employee Name is required.',
                'max_length' => 'Employee Name cannot exceed 150 characters.',
            ],
            'contractor_id' => [
                'is_natural_no_zero' => 'Please select a valid contractor.',
                'is_not_unique'     => 'The selected contractor does not exist.',
            ],
            'biometric_code' => [
                'max_length' => 'Biometric Code cannot exceed 50 characters.',
                'is_unique'  => 'This Biometric Code is already assigned to another employee.',
            ],
            'phone_number' => [
                'max_length' => 'Phone Number cannot exceed 20 characters.',
                'is_unique'  => 'This Phone Number is already assigned to another employee.',
            ],
            'bank_account_number' => [
                'max_length' => 'Bank Account Number cannot exceed 50 characters.',
                'is_unique'  => 'This Bank Account Number is already assigned to another employee.',
            ],
            'gender' => [
                'required' => 'Gender selection is required.',
                'in_list'  => 'Please select a valid gender option.',
            ],
            'date_of_birth' => [
                'required'   => 'Date of Birth is required.',
                'valid_date' => 'Please enter a valid Date of Birth.',
            ],
            'date_of_joining' => [
                'required'   => 'Date of Joining is required.',
                'valid_date' => 'Please enter a valid Date of Joining.',
            ],
            'monthly_base_salary' => [
                'required'               => 'Monthly Base Salary is required.',
                'numeric'                => 'Monthly Base Salary must be a valid number.',
                'greater_than_equal_to'  => 'Monthly Base Salary cannot be negative.',
            ],
            'status' => [
                'required' => 'Status selection is required.',
                'in_list'  => 'Please select a valid status (Active, Relieved, or Inactive).',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please correct the highlighted validation errors.',
                    'errors'  => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status        = $this->request->getPost('status');
        $dateOfLeaving = trim((string) $this->request->getPost('date_of_leaving'));
        $exitReason    = trim((string) $this->request->getPost('exit_reason'));

        // Additional conditional business logic validation
        $customErrors = [];

        if ($status === 'relieved') {
            if (empty($dateOfLeaving)) {
                $customErrors['date_of_leaving'] = 'Date of Leaving is required when marking employee as Relieved.';
            }
            if (empty($exitReason)) {
                $customErrors['exit_reason'] = 'Exit Reason is required when marking employee as Relieved.';
            }
        } elseif (!empty($dateOfLeaving) && empty($exitReason)) {
            $customErrors['exit_reason'] = 'Exit Reason is required if Date of Leaving is provided.';
        }

        if (!empty($customErrors)) {
            $allErrs = array_merge($this->validator->getErrors(), $customErrors);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please correct the highlighted validation errors.',
                    'errors'  => $allErrs,
                ])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $allErrs);
        }

        // If status is active, ensure date of leaving and exit reason are cleared
        if ($status === 'active') {
            $dateOfLeaving = null;
            $exitReason    = null;
        }

        $contractorId = $this->request->getPost('contractor_id');

        $employeeData = [
            'contractor_id'       => !empty($contractorId) ? (int) $contractorId : null,
            'biometric_code'      => trim((string) $this->request->getPost('biometric_code')) ?: null,
            'phone_number'        => trim((string) $this->request->getPost('phone_number')) ?: null,
            'employee_name'       => trim((string) $this->request->getPost('employee_name')),
            'gender'              => $this->request->getPost('gender'),
            'date_of_birth'       => $this->request->getPost('date_of_birth'),
            'date_of_joining'     => $this->request->getPost('date_of_joining'),
            'date_of_leaving'     => !empty($dateOfLeaving) ? $dateOfLeaving : null,
            'exit_reason'         => !empty($exitReason) ? $exitReason : null,
            'designation'         => trim((string) $this->request->getPost('designation')) ?: null,
            'department'          => trim((string) $this->request->getPost('department')) ?: null,
            'monthly_base_salary' => (float) $this->request->getPost('monthly_base_salary'),
            'bank_name'           => trim((string) $this->request->getPost('bank_name')) ?: null,
            'bank_account_number' => trim((string) $this->request->getPost('bank_account_number')) ?: null,
            'ifsc_code'           => strtoupper(trim((string) $this->request->getPost('ifsc_code'))) ?: null,
            'pan_number'          => strtoupper(trim((string) $this->request->getPost('pan_number'))) ?: null,
            'aadhaar_number'      => trim((string) $this->request->getPost('aadhaar_number')) ?: null,
            'status'              => $status,
        ];

        if ($this->employeeModel->insert($employeeData)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'   => 'success',
                    'message'  => "Employee '{$employeeData['employee_name']}' created successfully.",
                    'redirect' => site_url('employees'),
                ]);
            }
            return redirect()->to('employees')->with('success', "Employee '{$employeeData['employee_name']}' created successfully.");
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to create employee. Please try again.',
            ])->setStatusCode(400);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create employee. Please try again.');
    }

    /**
     * View detailed employee information
     */
    public function view($id = null)
    {
        $employee = $this->employeeModel->getEmployeeWithContractor((int) $id);

        if (!$employee) {
            throw PageNotFoundException::forPageNotFound("Employee with ID {$id} not found.");
        }

        $data = [
            'title'    => 'View Employee - ' . $employee['employee_name'],
            'employee' => $employee,
        ];

        return view('employees/view', $data);
    }

    /**
     * Show form to edit an existing employee
     */
    public function edit($id = null)
    {
        $employee = $this->employeeModel->find($id);

        if (!$employee) {
            throw PageNotFoundException::forPageNotFound("Employee with ID {$id} not found.");
        }

        // Show active contractors + current assigned contractor (even if inactive)
        $contractorQuery = $this->contractorModel->where('status', 'active');
        if (!empty($employee['contractor_id'])) {
            $contractorQuery->orWhere('contractor_id', $employee['contractor_id']);
        }
        $contractors = $contractorQuery->orderBy('contractor_name', 'ASC')->findAll();

        $data = [
            'title'       => 'Edit Employee - ' . $employee['employee_name'],
            'employee'    => $employee,
            'contractors' => $contractors,
        ];

        return view('employees/edit', $data);
    }

    /**
     * Update employee details in database
     */
    public function update($id = null)
    {
        $employee = $this->employeeModel->find($id);

        if (!$employee) {
            throw PageNotFoundException::forPageNotFound("Employee with ID {$id} not found.");
        }

        $rules = [
            'employee_name'       => 'required|max_length[150]',
            'contractor_id'       => 'permit_empty|is_natural_no_zero|is_not_unique[contractors.contractor_id]',
            'biometric_code'      => "permit_empty|max_length[50]|is_unique[employees.biometric_code,employee_id,{$id}]",
            'phone_number'        => "permit_empty|max_length[20]|is_unique[employees.phone_number,employee_id,{$id}]",
            'gender'              => 'required|in_list[male,female,other]',
            'date_of_birth'       => 'required|valid_date',
            'date_of_joining'     => 'required|valid_date',
            'date_of_leaving'     => 'permit_empty|valid_date',
            'exit_reason'         => 'permit_empty|max_length[255]',
            'designation'         => 'permit_empty|max_length[100]',
            'department'          => 'permit_empty|max_length[100]',
            'monthly_base_salary' => 'required|numeric|greater_than_equal_to[0]',
            'bank_name'           => 'permit_empty|max_length[100]',
            'bank_account_number' => "permit_empty|max_length[50]|is_unique[employees.bank_account_number,employee_id,{$id}]",
            'ifsc_code'           => 'permit_empty|max_length[20]',
            'pan_number'          => 'permit_empty|max_length[20]',
            'aadhaar_number'      => 'permit_empty|max_length[20]',
            'status'              => 'required|in_list[active,relieved,inactive]',
        ];

        $messages = [
            'employee_name' => [
                'required'   => 'Employee Name is required.',
                'max_length' => 'Employee Name cannot exceed 150 characters.',
            ],
            'contractor_id' => [
                'is_natural_no_zero' => 'Please select a valid contractor.',
                'is_not_unique'     => 'The selected contractor does not exist.',
            ],
            'biometric_code' => [
                'max_length' => 'Biometric Code cannot exceed 50 characters.',
                'is_unique'  => 'This Biometric Code is already assigned to another employee.',
            ],
            'phone_number' => [
                'max_length' => 'Phone Number cannot exceed 20 characters.',
                'is_unique'  => 'This Phone Number is already assigned to another employee.',
            ],
            'bank_account_number' => [
                'max_length' => 'Bank Account Number cannot exceed 50 characters.',
                'is_unique'  => 'This Bank Account Number is already assigned to another employee.',
            ],
            'gender' => [
                'required' => 'Gender selection is required.',
                'in_list'  => 'Please select a valid gender option.',
            ],
            'date_of_birth' => [
                'required'   => 'Date of Birth is required.',
                'valid_date' => 'Please enter a valid Date of Birth.',
            ],
            'date_of_joining' => [
                'required'   => 'Date of Joining is required.',
                'valid_date' => 'Please enter a valid Date of Joining.',
            ],
            'monthly_base_salary' => [
                'required'              => 'Monthly Base Salary is required.',
                'numeric'               => 'Monthly Base Salary must be a valid number.',
                'greater_than_equal_to' => 'Monthly Base Salary cannot be negative.',
            ],
            'status' => [
                'required' => 'Status selection is required.',
                'in_list'  => 'Please select a valid status (Active, Relieved, or Inactive).',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please correct the highlighted validation errors.',
                    'errors'  => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status        = $this->request->getPost('status');
        $dateOfLeaving = trim((string) $this->request->getPost('date_of_leaving'));
        $exitReason    = trim((string) $this->request->getPost('exit_reason'));

        // Additional conditional business logic validation
        $customErrors = [];

        if ($status === 'relieved') {
            if (empty($dateOfLeaving)) {
                $customErrors['date_of_leaving'] = 'Date of Leaving is required when marking employee as Relieved.';
            }
            if (empty($exitReason)) {
                $customErrors['exit_reason'] = 'Exit Reason is required when marking employee as Relieved.';
            }
        } elseif (!empty($dateOfLeaving) && empty($exitReason)) {
            $customErrors['exit_reason'] = 'Exit Reason is required if Date of Leaving is provided.';
        }

        if (!empty($customErrors)) {
            $allErrs = array_merge($this->validator->getErrors(), $customErrors);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please correct the highlighted validation errors.',
                    'errors'  => $allErrs,
                ])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $allErrs);
        }

        // If status is active, clear date of leaving and exit reason
        if ($status === 'active') {
            $dateOfLeaving = null;
            $exitReason    = null;
        }

        $contractorId = $this->request->getPost('contractor_id');

        $updateData = [
            'contractor_id'       => !empty($contractorId) ? (int) $contractorId : null,
            'biometric_code'      => trim((string) $this->request->getPost('biometric_code')) ?: null,
            'phone_number'        => trim((string) $this->request->getPost('phone_number')) ?: null,
            'employee_name'       => trim((string) $this->request->getPost('employee_name')),
            'gender'              => $this->request->getPost('gender'),
            'date_of_birth'       => $this->request->getPost('date_of_birth'),
            'date_of_joining'     => $this->request->getPost('date_of_joining'),
            'date_of_leaving'     => !empty($dateOfLeaving) ? $dateOfLeaving : null,
            'exit_reason'         => !empty($exitReason) ? $exitReason : null,
            'designation'         => trim((string) $this->request->getPost('designation')) ?: null,
            'department'          => trim((string) $this->request->getPost('department')) ?: null,
            'monthly_base_salary' => (float) $this->request->getPost('monthly_base_salary'),
            'bank_name'           => trim((string) $this->request->getPost('bank_name')) ?: null,
            'bank_account_number' => trim((string) $this->request->getPost('bank_account_number')) ?: null,
            'ifsc_code'           => strtoupper(trim((string) $this->request->getPost('ifsc_code'))) ?: null,
            'pan_number'          => strtoupper(trim((string) $this->request->getPost('pan_number'))) ?: null,
            'aadhaar_number'      => trim((string) $this->request->getPost('aadhaar_number')) ?: null,
            'status'              => $status,
        ];

        if ($this->employeeModel->skipValidation(true)->update($id, $updateData)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'   => 'success',
                    'message'  => "Employee '{$updateData['employee_name']}' updated successfully.",
                    'redirect' => site_url('employees'),
                ]);
            }
            return redirect()->to('employees')->with('success', "Employee '{$updateData['employee_name']}' updated successfully.");
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to update employee details. Please try again.',
            ])->setStatusCode(400);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update employee details. Please try again.');
    }

    /**
     * Update ONLY the monthly base salary of an employee via AJAX
     */
    public function updateSalary($id = null)
    {
        $employee = $this->employeeModel->find($id);

        if (!$employee) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Employee not found.',
            ])->setStatusCode(404);
        }

        $salary = $this->request->getPost('monthly_base_salary');

        if ($salary === null || !is_numeric($salary) || (float) $salary < 0) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Monthly Base Salary must be a valid non-negative number.',
            ])->setStatusCode(400);
        }

        $newSalary = (float) $salary;

        if ($this->employeeModel->skipValidation(true)->update($id, ['monthly_base_salary' => $newSalary])) {
            return $this->response->setJSON([
                'status'           => 'success',
                'message'          => "Salary for '{$employee['employee_name']}' updated to ₹" . number_format($newSalary, 2) . " successfully.",
                'formatted_salary' => '₹' . number_format($newSalary, 2),
                'raw_salary'       => $newSalary,
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Failed to update salary. Please try again.',
        ])->setStatusCode(500);
    }

    /**
     * Toggle status of an employee strictly between Active <-> Inactive via POST
     */
    public function toggleStatus($id = null)
    {
        $employee = $this->employeeModel->find($id);

        if (!$employee) {
            return redirect()->to('employees')->with('error', 'Employee not found.');
        }

        if ($employee['status'] === 'relieved') {
            return redirect()->to('employees')->with('error', "Employee '{$employee['employee_name']}' is marked as Relieved and cannot be toggled via quick action. Please edit employee details to change status.");
        }

        $newStatus  = ($employee['status'] === 'active') ? 'inactive' : 'active';
        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';

        if ($this->employeeModel->skipValidation(true)->update($id, ['status' => $newStatus])) {
            return redirect()->to('employees')->with('success', "Employee '{$employee['employee_name']}' {$statusText} successfully.");
        }

        return redirect()->to('employees')->with('error', 'Failed to update employee status.');
    }

    /**
     * Delete employee via POST
     */
    public function delete($id = null)
    {
        $employee = $this->employeeModel->find($id);

        if (!$employee) {
            return redirect()->to('employees')->with('error', 'Employee not found.');
        }

        try {
            if ($this->employeeModel->delete($id)) {
                return redirect()->to('employees')->with('success', "Employee '{$employee['employee_name']}' deleted successfully.");
            }
        } catch (\Exception $e) {
            return redirect()->to('employees')->with('error', 'Cannot delete employee. Related records exist or database restriction applied.');
        }

        return redirect()->to('employees')->with('error', 'Failed to delete employee.');
    }
}
