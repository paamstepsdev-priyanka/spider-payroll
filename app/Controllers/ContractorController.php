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
        $contractorName = trim((string)$this->request->getPost('contractor_name'));
        $phoneNumber    = trim((string)$this->request->getPost('phone_number'));
        $bankName       = trim((string)$this->request->getPost('bank_name'));
        $branchName     = trim((string)$this->request->getPost('branch_name'));
        $bankAccNo      = trim((string)$this->request->getPost('bank_account_number'));
        $ifscCode       = strtoupper(trim((string)$this->request->getPost('ifsc_code')));

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

        $contractorName = trim((string)$this->request->getPost('contractor_name'));
        $phoneNumber    = trim((string)$this->request->getPost('phone_number'));
        $bankName       = trim((string)$this->request->getPost('bank_name'));
        $branchName     = trim((string)$this->request->getPost('branch_name'));
        $bankAccNo      = trim((string)$this->request->getPost('bank_account_number'));
        $ifscCode       = strtoupper(trim((string)$this->request->getPost('ifsc_code')));

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
}
