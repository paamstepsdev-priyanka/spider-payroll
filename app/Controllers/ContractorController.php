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
        $rules = [
            'contractor_name'     => 'required|max_length[150]',
            'contractor_code'     => 'required|max_length[30]|is_unique[contractors.contractor_code]',
            'phone_number'        => 'permit_empty|max_length[20]|is_unique[contractors.phone_number]',
            'email'               => 'permit_empty|valid_email|max_length[100]',
            'address'             => 'permit_empty',
            'bank_account_number' => 'required|max_length[50]|is_unique[contractors.bank_account_number]',
            'ifsc_code'           => 'required|max_length[20]|is_unique[contractors.ifsc_code]',
            'status'              => 'required|in_list[active,inactive]',
        ];

        $messages = [
            'contractor_name' => [
                'required'   => 'Contractor Name is required.',
                'max_length' => 'Contractor Name cannot exceed 150 characters.',
            ],
            'contractor_code' => [
                'required'   => 'Contractor Code is required.',
                'max_length' => 'Contractor Code cannot exceed 30 characters.',
                'is_unique'  => 'This Contractor Code is already registered. Please enter a unique code.',
            ],
            'phone_number' => [
                'max_length' => 'Phone Number cannot exceed 20 characters.',
                'is_unique'  => 'This Phone Number is already registered by another contractor.',
            ],
            'email' => [
                'valid_email' => 'Please enter a valid email address.',
                'max_length'  => 'Email cannot exceed 100 characters.',
            ],
            'bank_account_number' => [
                'required'   => 'Bank Account Number is required.',
                'max_length' => 'Bank Account Number cannot exceed 50 characters.',
                'is_unique'  => 'This Bank Account Number is already registered by another contractor.',
            ],
            'ifsc_code' => [
                'required'   => 'IFSC Code is required.',
                'max_length' => 'IFSC Code cannot exceed 20 characters.',
                'is_unique'  => 'This IFSC Code is already registered by another contractor.',
            ],
            'status' => [
                'required' => 'Status selection is required.',
                'in_list'  => 'Please select a valid status.',
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

        $contractorData = [
            'contractor_name'     => trim($this->request->getPost('contractor_name')),
            'contractor_code'     => trim($this->request->getPost('contractor_code')),
            'phone_number'        => trim($this->request->getPost('phone_number')),
            'email'               => trim($this->request->getPost('email')),
            'address'             => trim($this->request->getPost('address')),
            'bank_account_number' => trim($this->request->getPost('bank_account_number')),
            'ifsc_code'           => strtoupper(trim($this->request->getPost('ifsc_code'))),
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
            throw PageNotFoundException::forPageNotFound("Contractor with ID {$id} not found.");
        }

        $data = [
            'title'      => 'View Contractor - ' . $contractor['contractor_name'],
            'contractor' => $contractor,
        ];

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

        $rules = [
            'contractor_name'     => 'required|max_length[150]',
            'contractor_code'     => "required|max_length[30]|is_unique[contractors.contractor_code,contractor_id,{$id}]",
            'phone_number'        => "permit_empty|max_length[20]|is_unique[contractors.phone_number,contractor_id,{$id}]",
            'email'               => 'permit_empty|valid_email|max_length[100]',
            'address'             => 'permit_empty',
            'bank_account_number' => "required|max_length[50]|is_unique[contractors.bank_account_number,contractor_id,{$id}]",
            'ifsc_code'           => "required|max_length[20]|is_unique[contractors.ifsc_code,contractor_id,{$id}]",
            'status'              => 'required|in_list[active,inactive]',
        ];

        $messages = [
            'contractor_name' => [
                'required'   => 'Contractor Name is required.',
                'max_length' => 'Contractor Name cannot exceed 150 characters.',
            ],
            'contractor_code' => [
                'required'   => 'Contractor Code is required.',
                'max_length' => 'Contractor Code cannot exceed 30 characters.',
                'is_unique'  => 'This Contractor Code is already registered by another contractor.',
            ],
            'phone_number' => [
                'max_length' => 'Phone Number cannot exceed 20 characters.',
                'is_unique'  => 'This Phone Number is already registered by another contractor.',
            ],
            'email' => [
                'valid_email' => 'Please enter a valid email address.',
                'max_length'  => 'Email cannot exceed 100 characters.',
            ],
            'bank_account_number' => [
                'required'   => 'Bank Account Number is required.',
                'max_length' => 'Bank Account Number cannot exceed 50 characters.',
                'is_unique'  => 'This Bank Account Number is already registered by another contractor.',
            ],
            'ifsc_code' => [
                'required'   => 'IFSC Code is required.',
                'max_length' => 'IFSC Code cannot exceed 20 characters.',
                'is_unique'  => 'This IFSC Code is already registered by another contractor.',
            ],
            'status' => [
                'required' => 'Status selection is required.',
                'in_list'  => 'Please select a valid status.',
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

        $updateData = [
            'contractor_name'     => trim($this->request->getPost('contractor_name')),
            'contractor_code'     => trim($this->request->getPost('contractor_code')),
            'phone_number'        => trim($this->request->getPost('phone_number')),
            'email'               => trim($this->request->getPost('email')),
            'address'             => trim($this->request->getPost('address')),
            'bank_account_number' => trim($this->request->getPost('bank_account_number')),
            'ifsc_code'           => strtoupper(trim($this->request->getPost('ifsc_code'))),
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
