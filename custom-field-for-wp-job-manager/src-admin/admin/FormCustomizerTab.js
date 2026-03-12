import { useState, useEffect } from "react";
import {
  Button,
  Modal,
  TextControl,
  TextareaControl,
  SelectControl,
  CheckboxControl,
  Spinner,
} from "@wordpress/components";
import { fetchData, saveData } from "./api";

const FormCustomizer = ({ openPopup }) => {
  const [isOpen, setIsOpen] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const [loading, setLoading] = useState(true);
  const [fields, setFields] = useState([]);
  const [error, setError] = useState("");
  const [formData, setFormData] = useState({
    id: null,
    field_type_cfwjm: "text",
    field_location_cfwjm: "job",
    field_title: "",
    field_placeholder_cfwjm: "",
    field_class_cfwjm: "",
    field_min_cfwjm: "",
    field_max_cfwjm: "",
    field_step_cfwjm: "",
    field_description_cfwjm: "",
    field_hide_admin_cfwjm: false,
    field_hide_frontend_cfwjm: false,
    field_option_cfwjm: "",
    field_ordernumber_cfwjm: "1",
    field_location_show_cfwjm: "single_job_listing_meta_before",
    field_required_cfwjm: false,
    field_output_cfwjm: "",
    field_use_output_cfwjm: false,
  });

  useEffect(() => {
    fetchFields();
  }, []);

  const fetchFields = () => {
    fetchData(cfwjm_wp_ajax.get_fields, cfwjm_wp_ajax.nonce)
      .then((fetchedData) => {
        setFields(fetchedData);
        setLoading(false);
      })
      .catch((error) => {
        setLoading(false);
        console.error("Error fetching fields:", error);
      });
  };

  const openModal = (field = null) => {
    setIsOpen(true);
    if (field) {
      console.log("field",field);
      setFormData({
        id: field.id,
        field_type_cfwjm: field.field_type_cfwjm,
        field_location_cfwjm: field.field_location_cfwjm,
        field_title: field.field_title,
        field_placeholder_cfwjm: field.field_placeholder_cfwjm ? field.field_placeholder_cfwjm : "",
        field_class_cfwjm: field.field_class_cfwjm ? field.field_class_cfwjm : "",
        field_min_cfwjm: field.field_min_cfwjm ? field.field_min_cfwjm : "",
        field_max_cfwjm: field.field_max_cfwjm ? field.field_max_cfwjm : "",
        field_step_cfwjm: field.field_step_cfwjm ? field.field_step_cfwjm : "",
        field_description_cfwjm: field.field_description_cfwjm ? field.field_description_cfwjm : "",
        field_hide_admin_cfwjm: field.field_hide_admin_cfwjm === 'yes' || field.field_hide_admin_cfwjm === true,
        field_hide_frontend_cfwjm: field.field_hide_frontend_cfwjm === 'yes' || field.field_hide_frontend_cfwjm === true,
        field_option_cfwjm: field.field_option_cfwjm ? field.field_option_cfwjm : "",
        field_ordernumber_cfwjm: field.field_ordernumber_cfwjm,
        field_location_show_cfwjm: field.field_location_show_cfwjm,
        field_required_cfwjm: field.field_required_cfwjm === "yes",
        field_output_cfwjm: field.field_output_cfwjm,
        field_use_output_cfwjm: field.field_use_output_cfwjm === 'yes' || field.field_use_output_cfwjm === true,
      });
    } else {
      resetForm();
    }
  };

  const closeModal = () => {
    setIsOpen(false);
    resetForm();
  };

  const resetForm = () => {
    setFormData({
      id: null,
      field_type_cfwjm: "text",
      field_location_cfwjm: "job",
      field_title: "",
      field_placeholder_cfwjm: "",
      field_class_cfwjm: "",
      field_min_cfwjm: "",
      field_max_cfwjm: "",
      field_step_cfwjm: "",
      field_description_cfwjm: "",
      field_hide_admin_cfwjm: false,
      field_hide_frontend_cfwjm: false,
      field_option_cfwjm: "",
      field_ordernumber_cfwjm: "1",
      field_location_show_cfwjm: "single_job_listing_meta_before",
      field_required_cfwjm: false,
      field_output_cfwjm: "",
      field_use_output_cfwjm: false,
    });
    setError("");
  };

  const handleInputChange = (field, value) => {
    setFormData({ ...formData, [field]: value });
  };

  const saveField = () => {
    setIsSaving(true);
    const apiUrl = formData.id ? cfwjm_wp_ajax.update_field : cfwjm_wp_ajax.add_field;
    saveData(apiUrl, cfwjm_wp_ajax.nonce, formData)
      .then((responseData) => {
        setIsSaving(false);
        if (responseData.success) {
          fetchFields();
          openPopup("Settings saved!");
          closeModal();
        } else {
          setError(responseData.message);
        }
      })
      .catch((error) => {
        setIsSaving(false);
        console.error("Error saving field:", error);
        openPopup("Failed to save field.");
      });
  };

  const deleteField = (id) => {
    if (!window.confirm("Are you sure you want to delete this field?")) return;

    saveData(cfwjm_wp_ajax.delete_field, cfwjm_wp_ajax.nonce, { id, action: "delete" })
      .then((responseData) => {
        if (responseData.success) {
          fetchFields();
          openPopup("Field deleted!");
        } else {
          openPopup("Failed to delete field.");
        }
      })
      .catch((error) => {
        console.error("Error deleting field:", error);
        openPopup("Error deleting field.");
      });
  };

  return (
    <div>
      <Button isPrimary  className="mt-10 mb-10" onClick={() => openModal()}>Add Field</Button>

      {loading ? (
        <Spinner />
      ) : (
        <table className="wp-list-table widefat striped">
          <thead>
            <tr>
              <th>Label</th>
              <th>Type</th>
              <th>Field Location</th>
              <th>Field Show Location</th>
              <th>Key Meta</th>
              <th>Field Order Number</th>
              <th>Required</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {fields.length > 0 ? (
              fields.map((field) => (
                <tr key={field.id}>
                  <td>{field.field_title}</td>
                  <td>{field.field_type_cfwjm}</td>
                  <td>{field.field_location_cfwjm}</td>
                  <td>{field.field_location_show_cfwjm}</td>
                  <td>_field_cfwjm{field.postdata.ID}</td>
                  <td>{field.field_ordernumber_cfwjm}</td>
                  <td>{field.field_required_cfwjm === "yes" ? "Yes" : "No"}</td>
                  <td>
                    <Button isPrimary onClick={() => openModal(field)}>Edit</Button>
                    <Button isDestructive onClick={() => deleteField(field.id)}>Delete</Button>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="8">No fields available.</td>
              </tr>
            )}
          </tbody>
        </table>
      )}

      {isOpen && (
        <Modal title="Custom Field Editor" onRequestClose={closeModal}>
          <SelectControl
            label="Field Type"
            value={formData.field_type_cfwjm}
            onChange={(value) => handleInputChange("field_type_cfwjm", value)}
            options={Object.entries(cfwjm_wp_ajax.fieldset_arr).map(([key, label]) => ({
              value: key,
              label: label + (key === 'text' || key === 'textarea' ? '' : ' (Pro)'),
              disabled: !(key === 'text' || key === 'textarea'),
            }))}
          />

          {/* Pro upgrade note with purchase link */}
          <div className="cfwjm-pro-note" style={{ marginBottom: 8 }}>
            <small>
              Unlock all field types in the Pro version. <a href="https://www.codesmade.com/contact-us/" target="_blank" rel="noopener">Buy Pro</a>
            </small>
          </div>

          <SelectControl
            label="Field Location"
            value={formData.field_location_cfwjm}
            onChange={(value) => handleInputChange("field_location_cfwjm", value)}
            options={[
              { label: "Job", value: "job" },
              { label: "Company", value: "company" },
            ]}
          />

          <TextControl
            label="Field Name"
            value={formData.field_title}
            onChange={(value) => handleInputChange("field_title", value)}
            placeholder="Enter Field Name"
          />

          <TextControl
            label="Custom Class"
            value={formData.field_class_cfwjm}
            onChange={(value) => handleInputChange("field_class_cfwjm", value)}
            placeholder="e.g. my-custom-class another-class"
          />

          <TextareaControl
            label="Description"
            value={formData.field_description_cfwjm}
            onChange={(value) => handleInputChange("field_description_cfwjm", value)}
            placeholder="Enter a short description/help text for this field"
          />

          <CheckboxControl
            label="Hide in Admin"
            checked={formData.field_hide_admin_cfwjm}
            onChange={(value) => handleInputChange("field_hide_admin_cfwjm", value)}
          />

          <CheckboxControl
            label="Hide in Frontend"
            checked={formData.field_hide_frontend_cfwjm}
            onChange={(value) => handleInputChange("field_hide_frontend_cfwjm", value)}
          />

          {!["select", "multiselect", "radio"].includes(formData.field_type_cfwjm) && (
            <TextControl
              label="Placeholder"
              value={formData.field_placeholder_cfwjm}
              onChange={(value) => handleInputChange("field_placeholder_cfwjm", value)}
              placeholder="Enter placeholder text"
            />
          )}

          {(["number", "range"].includes(formData.field_type_cfwjm)) && (
            <>
              <TextControl
                label="Minimum value"
                type="number"
                value={formData.field_min_cfwjm}
                onChange={(value) => handleInputChange("field_min_cfwjm", value)}
                placeholder="Minimum"
              />
              <TextControl
                label="Maximum value"
                type="number"
                value={formData.field_max_cfwjm}
                onChange={(value) => handleInputChange("field_max_cfwjm", value)}
                placeholder="Maximum"
              />
              <TextControl
                label="Step value"
                type="number"
                value={formData.field_step_cfwjm}
                onChange={(value) => handleInputChange("field_step_cfwjm", value)}
                placeholder="Step (optional)"
              />
            </>
          )}

          {["select", "multiselect", "radio"].includes(formData.field_type_cfwjm) && (
            <TextareaControl
              label="Field Options"
              value={formData.field_option_cfwjm}
              onChange={(value) => handleInputChange("field_option_cfwjm", value)}
              placeholder={`Option 1\nOption 2\nOption 3`}
            />
          )}

          <TextControl
            label="Field Order Number"
            type="number"
            value={formData.field_ordernumber_cfwjm}
            onChange={(value) => handleInputChange("field_ordernumber_cfwjm", value)}
          />

          <SelectControl
            label="Field Show Location"
            value={formData.field_location_show_cfwjm}
            onChange={(value) => handleInputChange("field_location_show_cfwjm", value)}
            options={Object.entries(cfwjm_wp_ajax.display_loc_arr).map(([key, label]) => ({
              value: key,
              label: label,
            }))}
          />

          {/* Field Required (Pro) - disabled for free version */}
          <CheckboxControl
            label="Field Required (Pro)"
            checked={formData.field_required_cfwjm}
            onChange={(value) => handleInputChange("field_required_cfwjm", value)}
            disabled={true}
          />
          {/* Pro upgrade note with purchase link for these features */}
          <div className="cfwjm-pro-note" style={{ marginBottom: 8 }}>
            <small>
              Unlock "Field Required" and custom output format in the Pro version. <a href="https://www.codesmade.com/store/custom-field-for-wp-job-manager-pro/" target="_blank" rel="noopener">Buy Pro</a>
            </small>
          </div>

          {/* Use custom output format (Pro) - disabled for free version */}
          <CheckboxControl
            label="Use custom output format (Pro)"
            checked={formData.field_use_output_cfwjm}
            onChange={(value) => handleInputChange("field_use_output_cfwjm", value)}
           
          />

          
          {formData.field_use_output_cfwjm && (
            <>
              <TextareaControl
                label="Output Format (Pro)"
                value={formData.field_output_cfwjm}
                onChange={(value) => handleInputChange("field_output_cfwjm", value)}
                placeholder='<div class="cfwjm_output"><strong>{label}:</strong> {value}</div>'
                disabled={true}
              />
              <div >
                {"{label}"} = Field Name <br />
                {"{value}"} = Field Value <br />
                <strong>If you do not set up this field, the default HTML format will be used</strong>
              </div>
              {/* Pro link shown here as well when Output Format is visible */}
              <div className="cfwjm-pro-note mb-10" >
                <small>
                  Custom output format is a Pro feature. <a href="https://www.codesmade.com/store/custom-field-for-wp-job-manager-pro/" target="_blank" rel="noopener">Buy Pro</a>
                </small>
              </div>
            </>
          )}

         
          {error && <div className="error-message">{error}</div>}

          <div className="modal-actions" style={{ display: 'flex', gap: '5px' }}>
            <Button isPrimary onClick={saveField} disabled={isSaving}>
              {isSaving ? "Saving..." : "Save Field"}
            </Button>
            <Button isSecondary onClick={closeModal}>Cancel</Button>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default FormCustomizer;
