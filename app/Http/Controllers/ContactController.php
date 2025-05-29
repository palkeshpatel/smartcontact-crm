<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactMerge;
use App\Traits\AjaxResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    use AjaxResponse;

    public function index()
    {
        return view('contacts.index');
    }

    public function getContacts(Request $request)
    {
        $query = Contact::with('customFields')->active();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate(10);

        // Transform contacts to include custom_fields for frontend compatibility
        $transformedContacts = $contacts->getCollection()->map(function ($contact) {
            $contactArray = $contact->toArray();
            $contactArray['custom_fields'] = $contact->customFields->toArray();
            return $contactArray;
        });

        return $this->successResponse('Contacts retrieved successfully', [
            'contacts' => $transformedContacts,
            'pagination' => [
                'current_page' => $contacts->currentPage(),
                'last_page' => $contacts->lastPage(),
                'total' => $contacts->total()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_file' => 'nullable|file|max:5120'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['name', 'email', 'phone', 'gender']);

            if ($request->hasFile('profile_image')) {
                $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
            }

            if ($request->hasFile('additional_file')) {
                $data['additional_file'] = $request->file('additional_file')->store('additional_files', 'public');
            }

            $contact = Contact::create($data);

            if ($request->has('custom_fields')) {
                foreach ($request->custom_fields as $fieldName => $fieldValue) {
                    if ($fieldValue) {
                        $contact->setCustomField($fieldName, $fieldValue);
                    }
                }
            }

            DB::commit();
            return $this->successResponse('Contact created successfully', $contact->load('customFields'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create contact: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $contact = Contact::with('customFields', 'mergeHistory')->find($id);

        if (!$contact) {
            return $this->errorResponse('Contact not found', null, 404);
        }

        // Transform contact to include custom_fields for frontend compatibility
        $contactArray = $contact->toArray();
        $contactArray['custom_fields'] = $contact->customFields->toArray();

        return $this->successResponse('Contact retrieved successfully', $contactArray);
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return $this->errorResponse('Contact not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_file' => 'nullable|file|max:5120',
            'remove_profile_image' => 'nullable|in:0,1',
            'remove_additional_file' => 'nullable|in:0,1'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        try {
            DB::beginTransaction();

            $data = $request->only(['name', 'email', 'phone', 'gender']);

            // Handle profile image removal
            if ($request->input('remove_profile_image') == '1') {
                if ($contact->profile_image) {
                    Storage::disk('public')->delete($contact->profile_image);
                }
                $data['profile_image'] = null;
            } elseif ($request->hasFile('profile_image')) {
                if ($contact->profile_image) {
                    Storage::disk('public')->delete($contact->profile_image);
                }
                $data['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
            }

            // Handle additional file removal
            if ($request->input('remove_additional_file') == '1') {
                if ($contact->additional_file) {
                    Storage::disk('public')->delete($contact->additional_file);
                }
                $data['additional_file'] = null;
            } elseif ($request->hasFile('additional_file')) {
                if ($contact->additional_file) {
                    Storage::disk('public')->delete($contact->additional_file);
                }
                $data['additional_file'] = $request->file('additional_file')->store('additional_files', 'public');
            }

            $contact->update($data);

            if ($request->has('custom_fields')) {
                foreach ($request->custom_fields as $fieldName => $fieldValue) {
                    if ($fieldValue) {
                        $contact->setCustomField($fieldName, $fieldValue);
                    }
                }
            }

            DB::commit();

            // Transform contact to include custom_fields for frontend compatibility
            $contactArray = $contact->fresh()->load('customFields')->toArray();
            $contactArray['custom_fields'] = $contact->customFields->toArray();

            return $this->successResponse('Contact updated successfully', $contactArray);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update contact: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return $this->errorResponse('Contact not found', null, 404);
        }

        try {
            if ($contact->profile_image) {
                Storage::disk('public')->delete($contact->profile_image);
            }
            if ($contact->additional_file) {
                Storage::disk('public')->delete($contact->additional_file);
            }

            $contact->delete();
            return $this->successResponse('Contact deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete contact: ' . $e->getMessage());
        }
    }

    public function getMergeData(Request $request)
    {
        $contactIds = $request->input('contact_ids', []);

        if (count($contactIds) !== 2) {
            return $this->errorResponse('Please select exactly 2 contacts to merge');
        }

        $contacts = Contact::with('customFields')->whereIn('id', $contactIds)->get();

        if ($contacts->count() !== 2) {
            return $this->errorResponse('One or more contacts not found');
        }

        return $this->successResponse('Merge data retrieved successfully', $contacts);
    }

    public function mergeContacts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'master_contact_id' => 'required|exists:contacts,id',
            'merge_contact_id' => 'required|exists:contacts,id',
            'conflict_resolutions' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        try {
            DB::beginTransaction();

            $masterContact = Contact::with('customFields')->find($request->master_contact_id);
            $mergeContact = Contact::with('customFields')->find($request->merge_contact_id);

            $mergedData = $mergeContact->toArray();
            $conflictResolutions = $request->conflict_resolutions ?? [];

            $additionalEmails = $masterContact->additional_emails ?? [];
            if ($mergeContact->email && $mergeContact->email !== $masterContact->email) {
                $additionalEmails[] = $mergeContact->email;
            }
            if ($mergeContact->additional_emails) {
                $additionalEmails = array_merge($additionalEmails, $mergeContact->additional_emails);
            }

            $additionalPhones = $masterContact->additional_phones ?? [];
            if ($mergeContact->phone && $mergeContact->phone !== $masterContact->phone) {
                $additionalPhones[] = $mergeContact->phone;
            }
            if ($mergeContact->additional_phones) {
                $additionalPhones = array_merge($additionalPhones, $mergeContact->additional_phones);
            }

            $masterContact->update([
                'additional_emails' => array_unique($additionalEmails),
                'additional_phones' => array_unique($additionalPhones)
            ]);

            foreach ($mergeContact->customFields as $customField) {
                $existingField = $masterContact->customFields()
                    ->where('field_name', $customField->field_name)
                    ->first();

                if (!$existingField) {
                    $masterContact->setCustomField(
                        $customField->field_name,
                        $customField->field_value,
                        $customField->field_type
                    );
                }
            }

            ContactMerge::create([
                'master_contact_id' => $masterContact->id,
                'merged_contact_id' => $mergeContact->id,
                'merged_data' => $mergedData,
                'conflict_resolutions' => $conflictResolutions
            ]);

            $mergeContact->update([
                'is_merged' => true,
                'merged_into' => $masterContact->id
            ]);

            DB::commit();
            return $this->successResponse('Contacts merged successfully', $masterContact->load('customFields'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to merge contacts: ' . $e->getMessage());
        }
    }
}
