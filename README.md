# Smart Contact CRM

A modern, feature-rich Contact Relationship Management (CRM) system built with Laravel and Bootstrap. This application provides comprehensive contact management with advanced features like custom fields, contact merging, and AJAX-powered interactions.

## Features

### ✅ Core CRUD Operations

-   **Create**: Add new contacts with standard and custom fields
-   **Read**: View contact details with pagination and filtering
-   **Update**: Edit existing contacts and their custom fields
-   **Delete**: Remove contacts with confirmation

### ✅ Standard Contact Fields

-   Name (required)
-   Email (required, unique)
-   Phone number
-   Gender (radio buttons: Male, Female, Other)
-   Profile image upload
-   Additional file upload

### ✅ Dynamic Custom Fields

-   Add unlimited custom fields per contact
-   Field types: text, date, etc.
-   Extensible database design for future field types
-   Dynamic UI for managing custom fields

### ✅ Advanced AJAX Integration

-   All CRUD operations use AJAX (no page reloads)
-   Real-time search and filtering
-   Instant success/error notifications
-   Smooth user experience with loading states

### ✅ Search & Filtering

-   Search by name and email
-   Filter by gender
-   Real-time results with AJAX
-   Clear filters functionality

### ✅ Contact Merging System

-   Select exactly 2 contacts for merging
-   Choose master contact in merge modal
-   Preserve all data from both contacts
-   Merge custom fields intelligently
-   Track merge history for audit trails
-   No data loss - secondary contact marked as merged

### ✅ Modern UI/UX

-   Bootstrap 5 responsive design
-   Font Awesome icons
-   Card-based contact layout
-   Hover effects and animations
-   Mobile-friendly interface
-   Toast notifications for user feedback

### ✅ Data Integrity

-   Database foreign key constraints
-   Proper validation on all inputs
-   File upload handling with storage management
-   Merge tracking and audit trails
-   Soft deletion concept for merged contacts

## Technical Implementation

### Database Schema

-   **contacts**: Main contact information with merge tracking
-   **contact_custom_fields**: Dynamic custom fields storage
-   **contact_merges**: Merge history and conflict resolution tracking

### Architecture Highlights

-   **Trait-based AJAX responses** for consistent API responses
-   **Eloquent relationships** for efficient data retrieval
-   **File storage management** with automatic cleanup
-   **Extensible custom fields** system
-   **Merge conflict resolution** with data preservation

## Installation & Setup

### Prerequisites

-   PHP 8.1+
-   Composer
-   MySQL/MariaDB
-   Web server (Apache/Nginx)

### Installation Steps

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd smartcontact-crm
    ```

2. **Install dependencies**

    ```bash
    composer install
    ```

3. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database configuration**

    - Update `.env` file with your database credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=smartcontact_crm
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. **Run migrations**

    ```bash
    php artisan migrate
    ```

6. **Create storage link**

    ```bash
    php artisan storage:link
    ```

7. **Seed sample data (optional)**

    ```bash
    php artisan db:seed --class=ContactSeeder
    ```

8. **Start the development server**

    ```bash
    php artisan serve
    ```

9. **Access the application**
    - Open your browser and navigate to `http://localhost:8000`

## Usage Guide

### Adding Contacts

1. Click the "Add Contact" button
2. Fill in the required fields (Name, Email)
3. Optionally add phone, gender, and file uploads
4. Add custom fields using the "Add Field" button
5. Submit the form

### Searching & Filtering

-   Use the search box to find contacts by name or email
-   Filter by gender using the dropdown
-   Results update in real-time

### Merging Contacts

1. Select exactly 2 contacts using the checkboxes
2. Click the "Merge Selected" button
3. Choose which contact should be the master
4. Confirm the merge operation
5. The secondary contact will be marked as merged and its data preserved

### Managing Custom Fields

-   Add custom fields when creating/editing contacts
-   Field names and values are flexible
-   Custom fields are preserved during merges
-   No limit on the number of custom fields

## File Structure

```
app/
├── Http/Controllers/
│   └── ContactController.php      # Main controller with CRUD and merge logic
├── Models/
│   ├── Contact.php               # Contact model with relationships
│   ├── ContactCustomField.php    # Custom fields model
│   └── ContactMerge.php          # Merge tracking model
└── Traits/
    └── AjaxResponse.php          # Standardized AJAX responses

database/
├── migrations/
│   ├── create_contacts_table.php
│   ├── create_contact_custom_fields_table.php
│   └── create_contact_merges_table.php
└── seeders/
    └── ContactSeeder.php         # Sample data seeder

resources/views/contacts/
└── index.blade.php               # Main application view

routes/
└── web.php                       # Application routes
```

## API Endpoints

-   `GET /` - Main application page
-   `GET /contacts/data` - Get contacts with pagination and filters
-   `POST /contacts` - Create new contact
-   `GET /contacts/{id}` - Get specific contact
-   `PUT /contacts/{id}` - Update contact
-   `DELETE /contacts/{id}` - Delete contact
-   `POST /contacts/merge/data` - Get merge preview data
-   `POST /contacts/merge` - Execute contact merge

## Development Notes

### Code Quality

-   Minimal comments as requested (only on complex logic)
-   Clean, readable code structure
-   Consistent naming conventions
-   Proper error handling

---

**Built with ❤️ using Laravel 12 and Bootstrap 5**
