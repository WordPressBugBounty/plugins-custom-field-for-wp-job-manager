=== Custom Field For WP Job Manager ===
Contributors: theme funda
Tags: wp job manager, custom fields, job manager field editor, job board, job form builder
Requires at least: 5.0
Tested up to: 6.9.1
Requires PHP: 7.4
Stable tag: 1.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The ultimate field editor for WP Job Manager. Easily add, edit, and manage custom job and company fields without any coding.

== Description ==

**Custom Field For WP Job Manager** is a powerful, lightweight, and SEO-friendly field editor designed to give you full control over your job board's submission forms. Whether you need to add a "Salary Range," "Working Hours," or "Required Experience," this plugin makes it possible in seconds.


### Why use this plugin?
Adding custom fields usually requires complex PHP coding and hooks. This plugin automates that entire process. It is built to be "developer-friendly" but "beginner-easy." It uses standard WP Job Manager filters (`submit_job_form_fields`) to ensure 100% compatibility with your theme and other add-ons.

### Essential Links
* [🌐 Live Demo](https://codesmade.com/demo/wpjobmanager/post-a-job/)
* [⚡ Upgrade to Pro](https://www.codesmade.com/store/custom-field-for-wp-job-manager-pro/)
* [🛠️ Priority Support](https://www.codesmade.com/contact-us/)

== Features ==

### 🛠️ 13+ Supported Field Types
Create a truly unique job board with a wide variety of input types:
* **Text & Textarea:** Perfect for titles, descriptions, or short notes.
* **Number & Range:** Ideal for salaries or percentage scales. (Pro)
* **Email, URL, & Telephone:** Capture contact info with built-in validation. (Pro)
* **Select & Multiselect:** Create dropdowns for categories or skill sets. (Pro)
* **Radio & Checkboxes:** Quick options for job types or "Agree to Terms." (Pro)
* **File Uploads:** Allow candidates to upload resumes or portfolios. (Pro)
* **WP Editor:** Enable the full WordPress Rich Text Editor for long descriptions. (Pro)

### ⚙️ Per-Field Advanced Settings
Every field you create comes with a suite of customization options:
* **Custom Placeholders:** Guide your users on what to type.
* **CSS Classes:** Easily style fields to match your theme’s design.
* **Input Constraints:** Set Minimum, Maximum, and Step values for numeric data.
* **Visibility Control:** Toggle visibility for "Frontend Forms" vs "Admin Meta Boxes."
* **Custom Output:** Use `{label}` and `{value}` placeholders to display data exactly how you want on the listing page. (Pro)

== How It Works ==

1. **Create:** Go to the "Form Customizer" tab in your WP Admin.
2. **Configure:** Click "Add Field," choose your type (e.g., Number), and set it as "Required."
3. **Display:** The field automatically appears on your frontend job submission form.
4. **View:** Once submitted, the data is displayed on the single job listing page using your custom format.

== Shortcode ==

You can display any saved custom field anywhere on your site using our flexible shortcode:

`[cm_fieldshow key="_field_cfwjm13" job_id="123"]`

* **key:** The meta key of your custom field (e.g., `_field_salary`).
* **job_id:** (Optional) Provide a specific Job ID, or leave blank to auto-detect the current post.

== Developer Notes ==

For developers looking to extend the plugin:
* **Storage:** Fields are stored as a Custom Post Type (`wpjmcf`).
* **Meta:** All settings are stored as post meta using the `_cfwjm` suffix.
* **Hooks:** We use the standard `submit_job_form_fields` filter

== Frequently Asked Questions ==

= Is this compatible with the latest WordPress version? =
Yes! We regularly test and update the plugin to ensure compatibility with WordPress 6.x and 7.x.

= Can I add fields to the Company form too? =
Absolutely. Our Form Customizer supports both Job and Company field injection.

= Does it support file uploads? =
Yes, the 'File' field type allows users to upload documents directly through the frontend form.

== Changelog ==

= 1.6.0 =
* NEW: Added support for Range and Telephone field types.
* IMPROVED: Faster React-based admin interface.
* FIXED: Validation bug on numeric 'step' attributes.

= 1.5.0 =
* Initial compatibility update for WP 6.9.
* Minor CSS fixes for frontend form alignment.

== Upgrade Notice ==

= 1.6.0 =
Recommended update for enhanced security and 2 new field types.