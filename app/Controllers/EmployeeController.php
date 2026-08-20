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
        $rawStatus    = $this->request->getGet('status');

        // Default status is 'active' if not provided in URL
        $status       = ($rawStatus === null) ? 'active' : trim((string) $rawStatus);
        $filterStatus = ($status === 'all' || $status === '') ? '' : $status;
        $sortColumn   = trim((string) $this->request->getGet('sort_column'));
        if (empty($sortColumn)) {
            $sortColumn = 'employee_id';
        }
        $sortOrder    = strtoupper(trim((string) $this->request->getGet('sort_order')));
        if ($sortOrder !== 'ASC' && $sortOrder !== 'DESC') {
            $sortOrder = 'DESC';
        }

        $perPage      = 10;

        $employees   = $this->employeeModel->getFilteredEmployees($search, $contractorId, $filterStatus, $sortColumn, $sortOrder, $perPage);
        $contractors = $this->contractorModel->orderBy('contractor_name', 'ASC')->findAll();

        // Helper to count status with search/contractor applied
        $countQuery = function($st = null) use ($search, $contractorId) {
            $builder = $this->employeeModel->builder();
            if (!empty($search)) {
                $builder->groupStart()
                    ->like('employee_name', $search)
                    ->orLike('biometric_code', $search)
                    ->groupEnd();
            }
            if (!empty($contractorId)) {
                $builder->where('contractor_id', $contractorId);
            }
            if (!empty($st)) {
                $builder->where('status', $st);
            }
            return $builder->countAllResults();
        };

        $statusCounts = [
            'all'      => $countQuery(),
            'active'   => $countQuery('active'),
            'inactive' => $countQuery('inactive'),
            'relieved' => $countQuery('relieved'),
        ];

        $data = [
            'title'        => 'Employees - Spider Payroll',
            'employees'    => $employees,
            'contractors'  => $contractors,
            'pager'        => $this->employeeModel->pager,
            'search'       => $search,
            'contractorId' => $contractorId,
            'status'       => $status,
            'statusCounts' => $statusCounts,
            'sortColumn'   => $sortColumn,
            'sortOrder'    => $sortOrder,
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
        $dob           = trim((string) $this->request->getPost('date_of_birth'));
        $status        = $this->request->getPost('status');
        $dateOfLeaving = trim((string) $this->request->getPost('date_of_leaving'));
        $exitReason    = trim((string) $this->request->getPost('exit_reason'));

        $bankName          = trim((string) $this->request->getPost('bank_name'));
        $accountHolderName = trim((string) $this->request->getPost('account_holder_name'));
        $bankBranch        = trim((string) $this->request->getPost('bank_branch'));
        $bankAccNo         = trim((string) $this->request->getPost('bank_account_number'));
        $ifscCode          = strtoupper(trim((string) $this->request->getPost('ifsc_code')));
        $panNo             = strtoupper(trim((string) $this->request->getPost('pan_number')));

        $errors = [];
        if (!empty($biometricCode) && $this->employeeModel->where('biometric_code', $biometricCode)->first()) {
            $errors['biometric_code'] = 'This Biometric Code is already registered with another employee.';
        }
        if (!empty($phoneNo) && $this->employeeModel->where('phone_number', $phoneNo)->first()) {
            $errors['phone_number'] = 'This Phone Number is already registered with another employee.';
        }

        if (empty($bankName)) {
            $errors['bank_name'] = 'Bank Name is required.';
        }
        if (empty($accountHolderName)) {
            $errors['account_holder_name'] = 'Account Holder Name is required.';
        }
        if (empty($bankBranch)) {
            $errors['bank_branch'] = 'Bank Branch is required.';
        }

        if (empty($bankAccNo)) {
            $errors['bank_account_number'] = 'Bank Account Number is required.';
        } elseif (strlen($bankAccNo) < 9) {
            $errors['bank_account_number'] = 'Bank Account Number must be at least 9 characters.';
        } elseif ($this->employeeModel->where('bank_account_number', $bankAccNo)->first()) {
            $errors['bank_account_number'] = 'This Bank Account Number is already registered with another employee.';
        }

        if (empty($ifscCode)) {
            $errors['ifsc_code'] = 'IFSC Code is required.';
        } elseif (!preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifscCode)) {
            $errors['ifsc_code'] = 'Please enter a valid 11-character IFSC Code (e.g. SBIN0000005).';
        } elseif ($this->employeeModel->where('ifsc_code', $ifscCode)->first()) {
            $errors['ifsc_code'] = 'This IFSC Code is already registered with another employee.';
        }

        if (!empty($panNo) && $this->employeeModel->where('pan_number', $panNo)->first()) {
            $errors['pan_number'] = 'This PAN Number is already registered with another employee.';
        }

        if (!empty($errors)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please correct the highlighted errors.',
                    'errors'  => $errors,
                ])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // If status is active, ensure date of leaving and exit reason are cleared
        if ($status === 'active') {
            $dateOfLeaving = null;
            $exitReason    = null;
        }

        $isDirectEmployee = $this->request->getPost('is_direct_employee') !== null ? (string)$this->request->getPost('is_direct_employee') : '0';
        if ($isDirectEmployee === '0') {
            $contractorId = null;
        } else {
            $rawContractorId = $this->request->getPost('contractor_id');
            $contractorId = !empty($rawContractorId) ? (int) $rawContractorId : null;
        }

        $employeeData = [
            'is_direct_employee' => $isDirectEmployee,
            'contractor_id'       => $contractorId,
            'biometric_code'      => trim((string) $this->request->getPost('biometric_code')) ?: null,
            'phone_number'        => trim((string) $this->request->getPost('phone_number')) ?: null,
            'employee_name'       => trim((string) $this->request->getPost('employee_name')),
            'gender'              => $this->request->getPost('gender'),
            'date_of_birth'       => !empty($dob) ? $dob : null,
            'date_of_joining'     => $this->request->getPost('date_of_joining'),
            'date_of_leaving'     => !empty($dateOfLeaving) ? $dateOfLeaving : null,
            'exit_reason'         => !empty($exitReason) ? $exitReason : null,
            'designation'         => trim((string) $this->request->getPost('designation')) ?: null,
            'department'          => trim((string) $this->request->getPost('department')) ?: null,
            'monthly_base_salary' => (float) $this->request->getPost('monthly_base_salary'),
            'bank_name'           => trim((string) $this->request->getPost('bank_name')) ?: null,
            'account_holder_name' => $accountHolderName,
            'bank_account_number' => trim((string) $this->request->getPost('bank_account_number')) ?: null,
            'ifsc_code'           => strtoupper(trim((string) $this->request->getPost('ifsc_code'))) ?: null,
            'bank_branch'         => trim((string) $this->request->getPost('bank_branch')) ?: null,
            'pan_number'          => strtoupper(trim((string) $this->request->getPost('pan_number'))) ?: null,
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

        $dob           = trim((string) $this->request->getPost('date_of_birth'));
        $status        = $this->request->getPost('status');
        $dateOfLeaving = trim((string) $this->request->getPost('date_of_leaving'));
        $exitReason    = trim((string) $this->request->getPost('exit_reason'));

        $biometricCode = trim((string) $this->request->getPost('biometric_code'));
        $phoneNo       = trim((string) $this->request->getPost('phone_number'));
        $bankName          = trim((string) $this->request->getPost('bank_name'));
        $accountHolderName = trim((string) $this->request->getPost('account_holder_name'));
        $bankBranch        = trim((string) $this->request->getPost('bank_branch'));
        $bankAccNo         = trim((string) $this->request->getPost('bank_account_number'));
        $ifscCode          = strtoupper(trim((string) $this->request->getPost('ifsc_code')));
        $panNo             = strtoupper(trim((string) $this->request->getPost('pan_number')));

        $errors = [];
        if (!empty($biometricCode) && $this->employeeModel->where('biometric_code', $biometricCode)->where('employee_id !=', $id)->first()) {
            $errors['biometric_code'] = 'This Biometric Code is already registered with another employee.';
        }
        if (!empty($phoneNo) && $this->employeeModel->where('phone_number', $phoneNo)->where('employee_id !=', $id)->first()) {
            $errors['phone_number'] = 'This Phone Number is already registered with another employee.';
        }

        if (empty($bankName)) {
            $errors['bank_name'] = 'Bank Name is required.';
        }
        if (empty($accountHolderName)) {
            $errors['account_holder_name'] = 'Account Holder Name is required.';
        }
        if (empty($bankBranch)) {
            $errors['bank_branch'] = 'Bank Branch is required.';
        }

        if (empty($bankAccNo)) {
            $errors['bank_account_number'] = 'Bank Account Number is required.';
        } elseif (strlen($bankAccNo) < 9) {
            $errors['bank_account_number'] = 'Bank Account Number must be at least 9 characters.';
        } elseif ($this->employeeModel->where('bank_account_number', $bankAccNo)->where('employee_id !=', $id)->first()) {
            $errors['bank_account_number'] = 'This Bank Account Number is already registered with another employee.';
        }

        if (empty($ifscCode)) {
            $errors['ifsc_code'] = 'IFSC Code is required.';
        } elseif (!preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifscCode)) {
            $errors['ifsc_code'] = 'Please enter a valid 11-character IFSC Code (e.g. SBIN0000005).';
        } elseif ($this->employeeModel->where('ifsc_code', $ifscCode)->where('employee_id !=', $id)->first()) {
            $errors['ifsc_code'] = 'This IFSC Code is already registered with another employee.';
        }

        if (!empty($panNo) && $this->employeeModel->where('pan_number', $panNo)->where('employee_id !=', $id)->first()) {
            $errors['pan_number'] = 'This PAN Number is already registered with another employee.';
        }

        if (!empty($errors)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please correct the highlighted errors.',
                    'errors'  => $errors,
                ])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // If status is active, clear date of leaving and exit reason
        if ($status === 'active') {
            $dateOfLeaving = null;
            $exitReason    = null;
        }

        $isDirectEmployee = $this->request->getPost('is_direct_employee') !== null ? (string)$this->request->getPost('is_direct_employee') : '0';
        if ($isDirectEmployee === '0') {
            $contractorId = null;
        } else {
            $rawContractorId = $this->request->getPost('contractor_id');
            $contractorId = !empty($rawContractorId) ? (int) $rawContractorId : null;
        }

        $updateData = [
            'is_direct_employee' => $isDirectEmployee,
            'contractor_id'       => $contractorId,
            'biometric_code'      => trim((string) $this->request->getPost('biometric_code')) ?: null,
            'phone_number'        => trim((string) $this->request->getPost('phone_number')) ?: null,
            'employee_name'       => trim((string) $this->request->getPost('employee_name')),
            'gender'              => $this->request->getPost('gender'),
            'date_of_birth'       => !empty($dob) ? $dob : null,
            'date_of_joining'     => $this->request->getPost('date_of_joining'),
            'date_of_leaving'     => !empty($dateOfLeaving) ? $dateOfLeaving : null,
            'exit_reason'         => !empty($exitReason) ? $exitReason : null,
            'designation'         => trim((string) $this->request->getPost('designation')) ?: null,
            'department'          => trim((string) $this->request->getPost('department')) ?: null,
            'monthly_base_salary' => (float) $this->request->getPost('monthly_base_salary'),
            'bank_name'           => trim((string) $this->request->getPost('bank_name')) ?: null,
            'account_holder_name' => $accountHolderName,
            'bank_account_number' => trim((string) $this->request->getPost('bank_account_number')) ?: null,
            'ifsc_code'           => strtoupper(trim((string) $this->request->getPost('ifsc_code'))) ?: null,
            'bank_branch'         => trim((string) $this->request->getPost('bank_branch')) ?: null,
            'pan_number'          => strtoupper(trim((string) $this->request->getPost('pan_number'))) ?: null,
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
