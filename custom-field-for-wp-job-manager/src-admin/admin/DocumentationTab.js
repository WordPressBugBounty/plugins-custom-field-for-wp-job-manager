import React from 'react';
import { Panel, PanelBody, PanelRow } from '@wordpress/components';

const Section = ({ title, children }) => (
  <PanelBody title={title} initialOpen={true} className="cfwjm-doc-section">
    <PanelRow>{children}</PanelRow>
  </PanelBody>
);

const DocumentationTab = () => {
  return (
    <div className="cfwjm-docs">
      <Panel>
        <Section title="Overview">
          <div>
            <p>
              Custom Field for WP Job Manager adds a lightweight field manager that lets administrators
              create extra fields for job and company forms. Fields are stored as a custom post type
              (<strong>wpjmcf</strong>) and are injected into WP Job Manager forms and single listing output.
            </p>
          </div>
        </Section>

        <Section title="Admin (how it works)">
          <div>
            <ol>
              <li>Open the plugin page and choose the <strong>Form Customizer</strong> tab.</li>
              <li>Click <strong>Add Field</strong> to open the editor modal and enter a Field Name.</li>
              <li>Choose a <strong>Field Type</strong>. Depending on type you will see different options:
                <ul>
                  <li>Text/Email/URL/Telephone: placeholder and class are available.</li>
                  <li>Number/Range: Minimum, Maximum and Step inputs appear.</li>
                  <li>Select/Multiselect/Radio: enter options (one per line).</li>
                  <li>File and WP Editor types have separate handling for uploads and rich text.</li>
                </ul>
              </li>
              <li>Optional flags:
                <ul>
                  <li><strong>Hide in Admin</strong> — field won't render in admin post meta boxes.</li>
                  <li><strong>Hide in Frontend</strong> — field will be skipped on frontend forms.</li>
                  <li><strong>Use custom output format</strong> — when checked, you can provide custom HTML using <code>{"{label}"}</code> and <code>{"{value}"}</code>; otherwise default markup is used.</li>
                </ul>
              </li>
              <li>Save the field. Fields are published as posts; the order number controls display order.</li>
            </ol>
          </div>
        </Section>

        <Section title="Frontend (how fields are injected & rendered)">
          <div>
            <p>
              Fields are injected into WP Job Manager via the <code>submit_job_form_fields</code> filter.
              Each field appears as an array with keys such as <code>label</code>, <code>type</code>, <code>placeholder</code>,
              <code>class</code>, <code>description</code>, and <code>custom_attributes</code> (used for min/max/step/type).
            </p>
            <p>
              Important behaviour:
            </p>
            <ul>
              <li>Fields with <strong>Hide in Frontend</strong> are skipped entirely.</li>
              <li>Numeric or HTML input types are conveyed via <code>custom_attributes['type']</code> so theme templates render correct input markup.</li>
              <li>If a field has <strong>Use custom output format</strong> enabled, the display renderer replaces <code>{"{label}"}</code> and <code>{"{value}"}</code> in the provided HTML and outputs it. If not enabled, the plugin prints a safe default wrapper.</li>
            </ul>
          </div>
        </Section>

        <Section title="Templates & Styling">
          <div>
            <p>
              The plugin provides templates in <code>templates/form-fields/</code>. Templates merge standard attributes and
              the field's <code>custom_attributes</code> so min/max/step/class and type are rendered correctly. Use the
              <code>field_class_cfwjm</code> to add custom classes that themes can target.
            </p>
          </div>
        </Section>

        <Section title="Field types & options">
          <div>
            <h4>Field types</h4>
            <ul>
              <li><strong>Text</strong>: single-line input. Supports <code>placeholder</code>, <code>class</code>, and <code>description</code>.</li>
              <li><strong>Textarea</strong>: multi-line text. Supports <code>placeholder</code>, <code>class</code>, and <code>description</code>.</li>
              <li><strong>Number</strong>: HTML <code>type="number"</code>. Accepts <code>min</code>, <code>max</code>, and <code>step</code> which are emitted as <code>custom_attributes</code>.</li>
              <li><strong>Range</strong>: HTML <code>type="range"</code>. Also uses <code>min</code>, <code>max</code>, and <code>step</code>.</li>
              <li><strong>Email</strong>: HTML <code>type="email"</code>. Useful for basic email validation in browsers.</li>
              <li><strong>URL</strong>: HTML <code>type="url"</code>. Browser-level URL validation is available.</li>
              <li><strong>Telephone</strong>: HTML <code>type="tel"</code>. No strict validation — useful for phone input patterns.</li>
              <li><strong>Select / Multiselect</strong>: dropdown(s). Enter options one-per-line in the admin; values are used as both label and value.</li>
              <li><strong>Radio</strong>: radio options entered one-per-line; useful for mutually exclusive choices.</li>
              <li><strong>Checkbox</strong>: single checkbox toggle saved as a value on submission.</li>
              <li><strong>File</strong>: file upload field. Admin-side renders uploads and frontend accepts file input; saved as attachment ID(s).</li>
              <li><strong>WP Editor</strong>: rich text editor for longer content; rendered with the WordPress editor in admin where applicable.</li>
            </ul>

            <h4>Field options (what they do)</h4>
            <ul>
              <li><strong>Field Name</strong> (<code>field_title</code>): visible label used for admin and default frontend output.</li>
              <li><strong>Placeholder</strong> (<code>field_placeholder_cfwjm</code>): short hint shown in inputs (not shown for select/radio types).</li>
              <li><strong>Custom Class</strong> (<code>field_class_cfwjm</code>): space-separated CSS classes added to the field for styling by theme or custom CSS.</li>
              <li><strong>Description</strong> (<code>field_description_cfwjm</code>): help text rendered near the input in forms and templates.</li>
              <li><strong>Minimum / Maximum / Step</strong> (<code>field_min_cfwjm</code>, <code>field_max_cfwjm</code>, <code>field_step_cfwjm</code>): numeric constraints applied to <code>number</code> and <code>range</code> inputs and emitted as <code>min</code>/<code>max</code>/<code>step</code> attributes.</li>
              <li><strong>Field Options</strong> (<code>field_option_cfwjm</code>): newline-separated list used by <code>select</code>, <code>multiselect</code>, and <code>radio</code>.</li>
              <li><strong>Field Order Number</strong> (<code>field_ordernumber_cfwjm</code>): numeric priority used to sort fields when injected into forms.</li>
              <li><strong>Field Show Location</strong> (<code>field_location_show_cfwjm</code>): choose where on the single listing the field output should appear (start/end/before/after meta blocks).</li>
              <li><strong>Required</strong> (<code>field_required_cfwjm</code>): mark the field as required in the frontend form; admin UI will show the required flag and the frontend will set the field as required attribute.</li>
              <li><strong>Hide in Admin</strong> (<code>field_hide_admin_cfwjm</code>): when enabled the field is not rendered in admin meta boxes (useful for frontend-only fields).</li>
              <li><strong>Hide in Frontend</strong> (<code>field_hide_frontend_cfwjm</code>): when enabled the field is not injected into frontend forms.</li>
              <li><strong>Use custom output format</strong> (<code>field_use_output_cfwjm</code>): toggle to enable <strong>Output Format</strong>. When enabled, the display renderer uses your HTML and replaces <code>{"{label}"}</code> and <code>{"{value}"}</code> placeholders.</li>
              <li><strong>Output Format</strong> (<code>field_output_cfwjm</code>): HTML template used to render saved values on single listing pages when <code>field_use_output_cfwjm</code> is enabled.</li>
            </ul>

            <h4>Saving & storage</h4>
            <p>Fields are saved as posts of type <code>wpjmcf</code>. Per-field settings are stored as post meta using keys shown above (e.g. <code>field_min_cfwjm</code>, <code>field_output_cfwjm</code>, etc.). Values submitted by users are saved to job/company posts as meta keys prefixed with <code>{"_field_cfwjm{POST_ID}"}</code>.</p>
          </div>
        </Section>

        <Section title="Shortcode usage">
          <div>
            <p>
              The plugin provides a shortcode to display a saved custom field value inside post content or templates.
            </p>
            <p><strong>Shortcode:</strong> <code>[cm_fieldshow key='_field_cfwjm13' job_id='15']</code></p>
            <h4>Attributes</h4>
            <ul>
              <li><code>key</code> (required) — the meta key to display, for example <code>_field_cfwjm13</code>.</li>
              <li><code>job_id</code> (optional) — the post ID to read the meta from; defaults to the current post ID when omitted.</li>
            </ul>
            <h4>Notes</h4>
            <ul>
              <li>If the <code>key</code> attribute is missing the shortcode returns: <em>Please Enter Key in Shortcode</em>.</li>
              <li>If the given <code>job_id</code> is not a <code>job_listing</code> post type the shortcode returns: <em>Post Type is Not Correct</em>.</li>
              <li>Array values (e.g., for multiselect) are joined with <code>, </code>.</li>
              <li>Output is sanitized via <code>wp_kses_post</code> and wrapped in <code>&lt;span class="cfwjm_output_shortcode"&gt;</code> so you can style it with CSS.
              </li>
            </ul>
            <h4>Examples</h4>
            <ul>
              <li>Show a field on the current job page: <code>[cm_fieldshow key='_field_cfwjm13']</code></li>
              <li>Show a field from a specific job: <code>[cm_fieldshow key='_field_cfwjm13' job_id='123']</code></li>
            </ul>
          </div>
        </Section>

        <Section title="Troubleshooting">
          <div>
            <ul>
              <li>If admin UI updates don't appear, rebuild the admin bundle and clear browser cache.</li>
              <li>Use valid HTML for custom output format — the plugin sanitizes output but malformed tags can break layout.</li>
              <li>Ensure required fields (Field Name and Order Number) are filled when saving.</li>
            </ul>
          </div>
        </Section>
      </Panel>
    </div>
  );
};

export default DocumentationTab;
