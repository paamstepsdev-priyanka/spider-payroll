<?php

namespace App\Controllers;

use App\Models\ContractorModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ContractorController extends BaseController
{
    protected ContractorModel $contractorModel;

    public function __construct()
    {
        $this->contractorModel = new ContractorModel();
    }

    /**
     * Display list of contractors with search & status filters (supports standard & AJAX requests)
     */
    public function index()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $perPage = 10;

        $contractors = $this->contractorModel->getFilteredContractors($search, $status, $perPage);

        $data = [
            'title'       => 'Contractors - Spider Payroll',
            'contractors' => $contractors,
            'pager'       => $this->contractorModel->pager,
            'search'      => $search,
            'status'      => $status,
        ];

        if ($this->request->isAJAX()) {
            return view('contractors/_table', $data);
        }

        return view('contractors/index', $data);
    }

    /**
     * Show form to create a new contractor
     */
    public function create()
    {
        $data = [
            'title' => 'Add Contractor - Spider Payroll',
        ];

        return view('contractors/create', $data);
    }

    /**
     * Store newly created contractor in database
     */
    public function store()
    {
        $contractorName    = trim((string)$this->request->getPost('contractor_name'));
        $phoneNumber       = trim((string)$this->request->getPost('phone_number'));
        $bankName          = trim((string)$this->request->getPost('bank_name'));
        $accountHolderName = trim((string)$this->request->getPost('account_holder_name'));
        $branchName        = trim((string)$this->request->getPost('branch_name'));
        $bankAccNo         = trim((string)$this->request->getPost('bank_account_number'));
        $ifscCode          = strtoupper(trim((string)$this->request->getPost('ifsc_code')));

        $errors = [];
        if (empty($contractorName)) {
            $errors['contractor_name'] = 'Contractor Name is required.';
        }
        if (!empty($phoneNumber) && $this->contractorModel->where('phone_number', $phoneNumber)->first()) {
            $errors['phone_number'] = 'This Phone Number is already registered with another contractor.';
        }

        if (empty($bankName)) {
            $errors['bank_name'] = 'Bank Name is required.';
        }
        if (empty($accountHolderName)) {
            $errors['account_holder_name'] = 'Account Holder Name is required.';
        }
        if (empty($branchName)) {
            $errors['branch_name'] = 'Branch Name is required.';
        }

        if (empty($bankAccNo)) {
            $errors['bank_account_number'] = 'Bank Account Number is required.';
        } elseif (strlen($bankAccNo) < 9) {
            $errors['bank_account_number'] = 'Bank Account Number must be at least 9 characters.';
        } elseif ($this->contractorModel->where('bank_account_number', $bankAccNo)->first()) {
            $errors['bank_account_number'] = 'This Bank Account Number is already registered with another contractor.';
        }

        if (empty($ifscCode)) {
            $errors['ifsc_code'] = 'IFSC Code is required.';
        } elseif (!preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifscCode)) {
            $errors['ifsc_code'] = 'Please enter a valid 11-character IFSC Code (e.g. SBIN0000005).';
        } elseif ($this->contractorModel->where('ifsc_code', $ifscCode)->first()) {
            $errors['ifsc_code'] = 'This IFSC Code is already registered with another contractor.';
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

        $contractorData = [
            'contractor_name'     => $contractorName,
            'phone_number'        => $phoneNumber ?: null,
            'dob'                 => trim((string)$this->request->getPost('dob')) ?: null,
            'email'               => trim((string)$this->request->getPost('email')) ?: null,
            'address'             => trim((string)$this->request->getPost('address')) ?: null,
            'bank_name'           => $bankName,
            'account_holder_name' => $accountHolderName,
            'branch_name'         => $branchName,
            'bank_account_number' => $bankAccNo,
            'ifsc_code'           => $ifscCode,
            'status'              => $this->request->getPost('status'),
        ];

        if ($this->contractorModel->insert($contractorData)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'   => 'success',
                    'message'  => 'Contractor registered successfully.',
                    'redirect' => site_url('contractors'),
                ]);
            }
            return redirect()->to('contractors')->with('success', 'Contractor registered successfully.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to create contractor. Please try again.',
            ])->setStatusCode(400);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create contractor. Please try again.');
    }

    /**
     * View contractor details
     */
    public function view($id = null)
    {
        $contractor = $this->contractorModel->find($id);

        if (!$contractor) {
            if ($this->request->isAJAX()) {
                return '<div class="alert alert-danger mb-0">Contractor with ID ' . esc($id) . ' not found.</div>';
            }
            throw PageNotFoundException::forPageNotFound("Contractor with ID {$id} not found.");
        }

        $data = [
            'title'      => 'View Contractor - ' . $contractor['contractor_name'],
            'contractor' => $contractor,
        ];

        if ($this->request->isAJAX()) {
            return view('contractors/_modal_view', $data);
        }

        return view('contractors/view', $data);
    }

    /**
     * Show form to edit contractor
     */
    public function edit($id = null)
    {
        $contractor = $this->contractorModel->find($id);

        if (!$contractor) {
            throw PageNotFoundException::forPageNotFound("Contractor with ID {$id} not found.");
        }

        $data = [
            'title'      => 'Edit Contractor - ' . $contractor['contractor_name'],
            'contractor' => $contractor,
        ];

        return view('contractors/edit', $data);
    }

    /**
     * Update contractor details in database
     */
    public function update($id = null)
    {
        $contractor = $this->contractorModel->find($id);

        if (!$contractor) {
            throw PageNotFoundException::forPageNotFound("Contractor with ID {$id} not found.");
        }

        $contractorName    = trim((string)$this->request->getPost('contractor_name'));
        $phoneNumber       = trim((string)$this->request->getPost('phone_number'));
        $bankName          = trim((string)$this->request->getPost('bank_name'));
        $accountHolderName = trim((string)$this->request->getPost('account_holder_name'));
        $branchName        = trim((string)$this->request->getPost('branch_name'));
        $bankAccNo         = trim((string)$this->request->getPost('bank_account_number'));
        $ifscCode          = strtoupper(trim((string)$this->request->getPost('ifsc_code')));

        $errors = [];
        if (empty($contractorName)) {
            $errors['contractor_name'] = 'Contractor Name is required.';
        }
        if (!empty($phoneNumber) && $this->contractorModel->where('phone_number', $phoneNumber)->where('contractor_id !=', $id)->first()) {
            $errors['phone_number'] = 'This Phone Number is already registered with another contractor.';
        }

        if (empty($bankName)) {
            $errors['bank_name'] = 'Bank Name is required.';
        }
        if (empty($accountHolderName)) {
            $errors['account_holder_name'] = 'Account Holder Name is required.';
        }
        if (empty($branchName)) {
            $errors['branch_name'] = 'Branch Name is required.';
        }

        if (empty($bankAccNo)) {
            $errors['bank_account_number'] = 'Bank Account Number is required.';
        } elseif (strlen($bankAccNo) < 9) {
            $errors['bank_account_number'] = 'Bank Account Number must be at least 9 characters.';
        } elseif ($this->contractorModel->where('bank_account_number', $bankAccNo)->where('contractor_id !=', $id)->first()) {
            $errors['bank_account_number'] = 'This Bank Account Number is already registered with another contractor.';
        }

        if (empty($ifscCode)) {
            $errors['ifsc_code'] = 'IFSC Code is required.';
        } elseif (!preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifscCode)) {
            $errors['ifsc_code'] = 'Please enter a valid 11-character IFSC Code (e.g. SBIN0000005).';
        } elseif ($this->contractorModel->where('ifsc_code', $ifscCode)->where('contractor_id !=', $id)->first()) {
            $errors['ifsc_code'] = 'This IFSC Code is already registered with another contractor.';
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

        $updateData = [
            'contractor_name'     => $contractorName,
            'phone_number'        => $phoneNumber ?: null,
            'dob'                 => trim((string)$this->request->getPost('dob')) ?: null,
            'email'               => trim((string)$this->request->getPost('email')) ?: null,
            'address'             => trim((string)$this->request->getPost('address')) ?: null,
            'bank_name'           => $bankName,
            'account_holder_name' => $accountHolderName,
            'branch_name'         => $branchName,
            'bank_account_number' => $bankAccNo,
            'ifsc_code'           => $ifscCode,
            'status'              => $this->request->getPost('status'),
        ];

        if ($this->contractorModel->skipValidation(true)->update($id, $updateData)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'   => 'success',
                    'message'  => 'Contractor details updated successfully.',
                    'redirect' => site_url('contractors'),
                ]);
            }
            return redirect()->to('contractors')->with('success', 'Contractor details updated successfully.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to update contractor details. Please try again.',
            ])->setStatusCode(400);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update contractor details. Please try again.');
    }

    /**
     * Toggle status of a contractor (active <-> inactive) via POST
     */
    public function toggleStatus($id = null)
    {
        $contractor = $this->contractorModel->find($id);

        if (!$contractor) {
            return redirect()->to('contractors')->with('error', 'Contractor not found.');
        }

        $newStatus  = ($contractor['status'] === 'active') ? 'inactive' : 'active';
        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';

        if ($this->contractorModel->skipValidation(true)->update($id, ['status' => $newStatus])) {
            return redirect()->to('contractors')->with('success', "Contractor '{$contractor['contractor_name']}' {$statusText} successfully.");
        }

        return redirect()->to('contractors')->with('error', 'Failed to update contractor status.');
    }

    /**
     * Delete contractor via POST
     */
    public function delete($id = null)
    {
        $contractor = $this->contractorModel->find($id);

        if (!$contractor) {
            return redirect()->to('contractors')->with('error', 'Contractor not found.');
        }

        if ($this->contractorModel->delete($id)) {
            return redirect()->to('contractors')->with('success', "Contractor '{$contractor['contractor_name']}' deleted successfully.");
        }

        return redirect()->to('contractors')->with('error', 'Failed to delete contractor.');
    }

    /**
     * Export contractors list in standard bank Excel format.
     */
    public function exportExcel()
    {
        $search       = $this->request->getGet('search');
        $status       = $this->request->getGet('status');
        $contractorId = $this->request->getGet('contractor_id');

        $employeeModel = new \App\Models\EmployeeModel();
        $builder = $employeeModel
            ->select('employees.*, contractors.contractor_name')
            ->join('contractors', 'contractors.contractor_id = employees.contractor_id', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('employees.employee_name', $search)
                    ->orLike('employees.account_holder_name', $search)
                    ->orLike('employees.bank_account_number', $search)
                    ->orLike('employees.ifsc_code', $search)
                    ->orLike('employees.biometric_code', $search)
                    ->orLike('contractors.contractor_name', $search)
                    ->groupEnd();
        }

        if (!empty($status)) {
            $builder->where('employees.status', $status);
        }

        if (!empty($contractorId)) {
            $builder->where('employees.contractor_id', $contractorId);
        }

        $contractorLabel = 'Contractors';
        if (!empty($contractorId)) {
            $c = $this->contractorModel->find((int)$contractorId);
            if ($c && !empty($c['contractor_name'])) {
                $contractorLabel = preg_replace('/[^A-Za-z0-9_\-]/', '_', trim($c['contractor_name']));
            }
        }
        $filename = "{$contractorLabel}_" . date('d_M_Y') . ".xls";

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Contractor Payout</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        echo '<style>';
        echo '  th { background-color: #FFF2CC !important; color: #000000; font-weight: bold; border: 0.5pt solid #B0C4DE; padding: 6px; text-align: center; font-family: Calibri, Arial, sans-serif; font-size: 11pt; }';
        echo '  td { border: 0.5pt solid #D3D3D3; padding: 5px; font-family: Calibri, Arial, sans-serif; font-size: 11pt; vertical-align: middle; }';
        echo '  .text-cell { mso-number-format:"\@"; }';
        echo '  .num-cell { mso-number-format:"0\.00"; text-align: right; }';
        echo '  .center-cell { text-align: center; }';
        echo '</style></head><body><table><thead><tr>';
        echo '  <th style="background-color: #FFF2CC;">SL No</th>';
        echo '  <th style="background-color: #FFF2CC;">Staff name (NAME AS PER BANK)</th>';
        echo '  <th style="background-color: #FFF2CC;">Staff No.</th>';
        echo '  <th style="background-color: #FFF2CC;">AC No</th>';
        echo '  <th style="background-color: #FFF2CC;">IFSC No</th>';
        echo '  <th style="background-color: #FFF2CC;">Salary</th>';
        echo '  <th style="background-color: #FFF2CC;">Attendance</th>';
        echo '  <th style="background-color: #FFF2CC;">Amount</th>';
        echo '  <th style="background-color: #FFF2CC;">Desc (Narration)</th>';
        echo '</tr></thead><tbody>';

        if (empty($employees)) {
            $cBuilder = $this->contractorModel;
            if (!empty($search)) {
                $cBuilder->groupStart()
                         ->like('contractor_name', $search)
                         ->orLike('phone_number', $search)
                         ->orLike('bank_account_number', $search)
                         ->orLike('ifsc_code', $search)
                         ->groupEnd();
            }
            if (!empty($status)) {
                $cBuilder->where('status', $status);
            }
            $contractors = $cBuilder->orderBy('contractor_name', 'ASC')->findAll();

            $sr = 1;
            foreach ($contractors as $row) {
                $accountHolder = !empty($row['account_holder_name']) ? htmlspecialchars($row['account_holder_name']) : htmlspecialchars($row['contractor_name']);
                $staffNo       = !empty($row['phone_number']) ? htmlspecialchars((string)$row['phone_number']) : htmlspecialchars((string)$row['contractor_id']);
                $accNo         = !empty($row['bank_account_number']) ? htmlspecialchars((string)$row['bank_account_number']) : '';
                $ifsc          = !empty($row['ifsc_code']) ? htmlspecialchars($row['ifsc_code']) : '';

                echo '<tr>';
                echo '  <td class="center-cell">' . $sr++ . '</td>';
                echo '  <td>' . $accountHolder . '</td>';
                echo '  <td class="text-cell">' . $staffNo . '</td>';
                echo '  <td class="text-cell">' . $accNo . '</td>';
                echo '  <td class="center-cell">' . $ifsc . '</td>';
                echo '  <td class="num-cell">0.00</td>';
                echo '  <td class="center-cell">0</td>';
                echo '  <td class="num-cell">0.00</td>';
                echo '  <td>Contractor Payout</td>';
                echo '</tr>';
            }
        } else {
            $sr = 1;
            foreach ($employees as $row) {
                $accountHolder = !empty($row['account_holder_name']) ? htmlspecialchars($row['account_holder_name']) : htmlspecialchars($row['employee_name']);
                $rawStaffNo    = !empty($row['biometric_code']) ? $row['biometric_code'] : (!empty($row['phone_number']) ? $row['phone_number'] : $row['employee_id']);
                $staffNo       = htmlspecialchars((string)$rawStaffNo);
                $accNo         = !empty($row['bank_account_number']) ? htmlspecialchars((string)$row['bank_account_number']) : '';
                $ifsc          = !empty($row['ifsc_code']) ? htmlspecialchars($row['ifsc_code']) : '';
                $baseSalary    = number_format((float) ($row['monthly_base_salary'] ?? 0), 2, '.', '');

                echo '<tr>';
                echo '  <td class="center-cell">' . $sr++ . '</td>';
                echo '  <td>' . $accountHolder . '</td>';
                echo '  <td class="text-cell">' . $staffNo . '</td>';
                echo '  <td class="text-cell">' . $accNo . '</td>';
                echo '  <td class="center-cell">' . $ifsc . '</td>';
                echo '  <td class="num-cell">' . $baseSalary . '</td>';
                echo '  <td class="center-cell">30</td>';
                echo '  <td class="num-cell">' . $baseSalary . '</td>';
                echo '  <td>Employee Payment</td>';
                echo '</tr>';
            }
        }

        echo '</tbody></table></body></html>';
        exit;
    }
}
