import React, { useState, useEffect } from 'react';
import './Main.css';

const projectTabs = [
  { id: 'overview', label: 'Quotation Overview', icon: 'fas fa-globe' },
  { id: 'details', label: 'Quotation Details', icon: 'fas fa-info-circle' },
  { id: 'items', label: 'Items', icon: 'fas fa-list' },
  { id: 'attachments', label: 'Attachments', icon: 'fas fa-paperclip' },
  { id: 'cost', label: 'Financials', icon: 'fas fa-dollar-sign' },
];

const ProjectPlanningPanel = ({
  selectedProject,
  activeTab,
  onTabChange,
  isCreatingNew,
  onSaveProject,
  onCancelProject,
}) => {
  const [projectData, setProjectData] = useState({
    ...selectedProject,
    rawData: selectedProject?.rawData || {},
  });

  useEffect(() => {
    setProjectData({
      ...selectedProject,
      rawData: selectedProject?.rawData || {},
    });
  }, [selectedProject]);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setProjectData((prev) => ({
      ...prev,
      [name]: value,
      rawData: {
        ...prev.rawData,
        [name]: value,
      },
    }));
  };

  const handleRawDataChange = (e) => {
    const { name, value } = e.target;
    setProjectData((prev) => ({
      ...prev,
      rawData: {
        ...prev.rawData,
        [name]: value,
      },
    }));
  };

  const handleItemChange = (index, field, value) => {
    setProjectData((prev) => {
      const updatedItems = [...prev.rawData.items];
      updatedItems[index] = { ...updatedItems[index], [field]: value };
      return {
        ...prev,
        rawData: {
          ...prev.rawData,
          items: updatedItems,
        },
      };
    });
  };

  const addItem = () => {
    setProjectData((prev) => ({
      ...prev,
      rawData: {
        ...prev.rawData,
        items: [
          ...(prev.rawData.items || []),
          {
            id: `item-${Date.now()}`,
            name: '',
            qty: 1,
            days: 7,
            price: 0,
            vendor: 'N/A',
            available: 0,
          },
        ],
      },
    }));
  };

  const removeItem = (index) => {
    setProjectData((prev) => {
      const updatedItems = prev.rawData.items.filter((_, i) => i !== index);
      return {
        ...prev,
        rawData: {
          ...prev.rawData,
          items: updatedItems,
        },
      };
    });
  };

  const handleSave = () => {
    onSaveProject(projectData);
    if (isCreatingNew) {
      onTabChange('overview');
    }
  };

  const handleCancel = () => {
    onCancelProject();
  };

  return (
    <div className="planning-panel">
      {/* Quotation Header */}
      <div className="event-detail-header">
        <div className="event-title-container">
          <h2>
            {isCreatingNew ? (
              <input
                type="text"
                name="title"
                value={projectData.title}
                onChange={handleInputChange}
                className="new-event-title-input"
                placeholder="Quotation Title"
              />
            ) : (
              projectData.title
            )}
          </h2>
          <div className={`event-status ${isCreatingNew ? 'draft-status' : ''}`}>
            {isCreatingNew ? 'Draft' : projectData.status}
          </div>
        </div>
        <div className="event-meta">
          <div className="meta-item">
            <i className="fas fa-building"></i>
            <span>
              {isCreatingNew ? (
                <input
                  type="text"
                  name="client"
                  value={projectData.client}
                  onChange={handleInputChange}
                  placeholder="Client Name"
                  className="new-event-input"
                />
              ) : (
                `Client: ${projectData.client}`
              )}
            </span>
          </div>
          <div className="meta-item">
            <i className="fas fa-user"></i>
            <span>
              {isCreatingNew ? (
                <input
                  type="text"
                  name="manager"
                  value={projectData.manager}
                  onChange={handleInputChange}
                  placeholder="Sales Manager"
                  className="new-event-input"
                />
              ) : (
                `Manager: ${projectData.manager}`
              )}
            </span>
          </div>
          <div className="meta-item">
            <i className="fas fa-calendar"></i>
            <span>
              {isCreatingNew ? (
                <input
                  type="date"
                  name="date"
                  value={projectData.date}
                  onChange={handleInputChange}
                  className="new-event-input"
                />
              ) : (
                `Date: ${projectData.date}`
              )}
            </span>
          </div>
        </div>
      </div>

      {/* Tabs Navigation */}
      <div className="tabs">
        {projectTabs.map((tab) => (
          <div
            key={tab.id}
            className={`tab ${activeTab === tab.id ? 'active' : ''}`}
            onClick={() => onTabChange(tab.id)}
            data-tab={tab.id}
          >
            <i className={tab.icon}></i>
            <span className="tab-label">{tab.label}</span>
          </div>
        ))}
      </div>

      {/* Tab Content Panels */}
      <div className="tab-content-container">
        {/* Quotation Overview Tab */}
    {activeTab === 'overview' && (
            <div className="tab-content active" id="overview-tab">
              <div className="overview-stats">
                <div className="overview-stat">
                  <div className="stat-label">Quotation Number</div>
                  <div className="stat-value">{projectData.rawData.quotation_number || 'N/A'}</div>
                </div>
                <div className="overview-stat">
                  <div className="stat-label">Total Amount</div>
                  <div className="stat-value">${projectData.rawData.total_amount || '0.00'}</div>
                </div>
                <div className="overview-stat">
                  <div className="stat-label">Client</div>
                  <div className="stat-value">{projectData.client || 'Not set'}</div>
                </div>
                <div className="overview-stat">
                  <div className="stat-label">Items</div>
                  <div className="stat-value">{projectData.rawData.items?.length || 0}</div>
                </div>
              </div>
              <div className="overview-grid">
                <div className="overview-card">
                  <h4>Quotation Details</h4>
                  <p>Status: {projectData.status}</p>
                  <p>Subtotal: ${projectData.rawData?.subtotal || '0.00'}</p>
                  <p>Discount: ${projectData.rawData?.discount || '0.00'}</p>
                  <p>Tax: ${projectData.rawData?.tax || '0.00'}</p>
                  <p>Setup Included: {projectData.rawData?.include_setup ? 'Yes' : 'No'}</p>
                  <p>Express Shipping: {projectData.rawData?.express_shipping ? 'Yes' : 'No'}</p>
                </div>

                <div className="overview-card">
                  <h4>Attachments</h4>

                  {/* Download PDF if available */}
                  {projectData.rawData?.pdfPath && (
                    <div style={{ marginBottom: '10px' }}>
                      <a
                        href={`http://127.0.0.1:8000/storage/${projectData.rawData.pdfPath}`}
                        download={projectData.rawData.pdfPath.split('/').pop()}
                        rel="nofollow noopener noreferrer"
                        className="btn btn-primary"
                      >
                        Download Quotation PDF
                      </a>
                    </div>
                  )}

                  <ul className="attachment-list">
                    {projectData.rawData?.attachments?.length > 0 ? (
                      projectData.rawData.attachments.map((attachment, index) => (
                        <li key={index}>
                          <i className="fas fa-file"></i>
                          <a
                            href={`http://127.0.0.1:8000/storage/${attachment.path}`}
                            download={attachment.name}
                            rel="nofollow noopener noreferrer"
                            title={`Download ${attachment.name}`}
                          >
                            <span>{attachment.name} ({attachment.size})</span>
                          </a>
                        </li>
                      ))
                    ) : (
                      <li>No attachments</li>
                    )}
                  </ul>
                </div>
              </div>
            </div>
          )}

        {/* Quotation Details Tab */}
        {activeTab === 'details' && (
          <div className="event-details-form">
            <div className="form-section">
              <div className="form-group">
                <label>Quotation Number</label>
                <input
                  type="text"
                  name="quotation_number"
                  value={projectData.rawData.quotation_number || ''}
                  onChange={handleRawDataChange}
                  placeholder="Quotation number"
                  className="form-control"
                  disabled={!isCreatingNew}
                />
              </div>
              <div className="form-group">
                <label>Project Title</label>
                <input
                  type="text"
                  name="title"
                  value={projectData.title}
                  onChange={handleInputChange}
                  placeholder="Project title"
                  className="form-control"
                />
              </div>
              <div className="form-group">
                <label>Client</label>
                <input
                  type="text"
                  name="client"
                  value={projectData.client}
                  onChange={handleInputChange}
                  placeholder="Client name"
                  className="form-control"
                />
              </div>
            </div>
            <div className="form-section">
              <div className="form-group">
                <label>Status</label>
                <select
                  name="status"
                  value={projectData.status}
                  onChange={handleInputChange}
                  className="form-control"
                >
                  <option value="Draft">Draft</option>
                  <option value="Sent">Sent</option>
                  <option value="Approved">Approved</option>
                  <option value="Rejected">Rejected</option>
                </select>
              </div>
              <div className="form-group">
                <label>Date</label>
                <input
                  type="date"
                  name="date"
                  value={projectData.date}
                  onChange={handleInputChange}
                  className="form-control"
                />
              </div>
              <div className="form-group">
                <label>Sales Manager</label>
                <input
                  type="text"
                  name="manager"
                  value={projectData.manager}
                  onChange={handleInputChange}
                  placeholder="Sales manager"
                  className="form-control"
                />
              </div>
            </div>
            <div className="form-group">
              <label>Front Page Content</label>
              <textarea
                name="front_page_content"
                value={projectData.rawData.front_page?.content || ''}
                onChange={(e) =>
                  setProjectData((prev) => ({
                    ...prev,
                    rawData: {
                      ...prev.rawData,
                      front_page: {
                        ...prev.rawData.front_page,
                        content: e.target.value,
                      },
                    },
                  }))
                }
                rows="4"
                placeholder="Front page content"
                className="form-control"
              />
            </div>
            <div className="form-group">
              <label>Terms & Conditions</label>
              <textarea
                name="back_page_content"
                value={projectData.rawData.back_page?.content || ''}
                onChange={(e) =>
                  setProjectData((prev) => ({
                    ...prev,
                    rawData: {
                      ...prev.rawData,
                      back_page: {
                        ...prev.rawData.back_page,
                        content: e.target.value,
                      },
                    },
                  }))
                }
                rows="4"
                placeholder="Terms & conditions"
                className="form-control"
              />
            </div>
            <div className="form-actions">
              <button className="btn btn-save" onClick={handleSave}>
                <i className="fas fa-save"></i>
                {isCreatingNew ? 'Create Quotation' : 'Save Changes'}
              </button>
              <button className="btn btn-cancel" onClick={handleCancel}>
                <i className="fas fa-times"></i> Cancel
              </button>
            </div>
          </div>
        )}

        {/* Items Tab */}
        {activeTab === 'items' && (
          <div className="tab-content active" id="items-tab">
            <h3>Quotation Items</h3>
            {projectData.rawData.items?.length > 0 ? (
              <div className="items-list">
                {projectData.rawData.items.map((item, index) => (
                  <div key={item.id} className="item-card">
                    <div className="form-group">
                      <label>Item Name</label>
                      <input
                        type="text"
                        value={item.name}
                        onChange={(e) => handleItemChange(index, 'name', e.target.value)}
                        placeholder="Item name"
                        className="form-control"
                      />
                    </div>
                    <div className="form-group">
                      <label>Quantity</label>
                      <input
                        type="number"
                        value={item.qty}
                        onChange={(e) => handleItemChange(index, 'qty', parseInt(e.target.value))}
                        min="1"
                        className="form-control"
                      />
                    </div>
                    <div className="form-group">
                      <label>Days</label>
                      <input
                        type="number"
                        value={item.days}
                        onChange={(e) => handleItemChange(index, 'days', parseInt(e.target.value))}
                        min="1"
                        className="form-control"
                      />
                    </div>
                    <div className="form-group">
                      <label>Price</label>
                      <input
                        type="number"
                        value={item.price}
                        onChange={(e) => handleItemChange(index, 'price', parseFloat(e.target.value))}
                        min="0"
                        className="form-control"
                      />
                    </div>
                    <button
                      className="btn btn-danger"
                      onClick={() => removeItem(index)}
                    >
                      <i className="fas fa-trash"></i> Remove
                    </button>
                  </div>
                ))}
              </div>
            ) : (
              <p>No items added.</p>
            )}
            <button className="btn btn-primary" onClick={addItem}>
              <i className="fas fa-plus"></i> Add Item
            </button>
            <div className="form-actions">
              <button className="btn btn-save" onClick={handleSave}>
                <i className="fas fa-save"></i> Save Changes
              </button>
            </div>
          </div>
        )}

        {/* Attachments Tab */}
        {activeTab === 'attachments' && (
          <div className="tab-content active" id="attachments-tab">
            <h3>Attachments</h3>
            {projectData.rawData.attachments?.length > 0 ? (
              <ul className="attachment-list">
                {projectData.rawData.attachments.map((attachment, index) => (
                  <li key={index} className="attachment-item">
                    <i className="fas fa-file"></i>
                    <span>{attachment.name} ({attachment.size})</span>
                    <span>Type: {attachment.type}</span>
                  </li>
                ))}
              </ul>
            ) : (
              <p>No attachments available.</p>
            )}
            <div className="form-group">
              <label>Upload New Attachment</label>
              <input
                type="file"
                onChange={(e) => {
                  const file = e.target.files[0];
                  if (file) {
                    setProjectData((prev) => ({
                      ...prev,
                      rawData: {
                        ...prev.rawData,
                        attachments: [
                          ...(prev.rawData.attachments || []),
                          {
                            name: file.name,
                            type: file.type,
                            size: `${(file.size / 1024).toFixed(2)} KB`,
                            path: `quotations/temp/${file.name}`,
                          },
                        ],
                      },
                    }));
                  }
                }}
                className="form-control"
              />
            </div>
            <div className="form-actions">
              <button className="btn btn-save" onClick={handleSave}>
                <i className="fas fa-save"></i> Save Changes
              </button>
            </div>
          </div>
        )}

        {/* Financials Tab */}
        {activeTab === 'cost' && (
          <div className="tab-content active" id="cost-tab">
            <h3>Financials</h3>
            <div className="form-section">
              <div className="form-group">
                <label>Subtotal</label>
                <input
                  type="number"
                  name="subtotal"
                  value={projectData.rawData.subtotal || ''}
                  onChange={handleRawDataChange}
                  min="0"
                  className="form-control"
                />
              </div>
              <div className="form-group">
                <label>Discount</label>
                <input
                  type="number"
                  name="discount"
                  value={projectData.rawData.discount || ''}
                  onChange={handleRawDataChange}
                  min="0"
                  className="form-control"
                />
              </div>
              <div className="form-group">
                <label>Tax</label>
                <input
                  type="number"
                  name="tax"
                  value={projectData.rawData.tax || ''}
                  onChange={handleRawDataChange}
                  min="0"
                  className="form-control"
                />
              </div>
              <div className="form-group">
                <label>Total Amount</label>
                <input
                  type="number"
                  name="total_amount"
                  value={projectData.rawData.total_amount || ''}
                  onChange={handleRawDataChange}
                  min="0"
                  className="form-control"
                />
              </div>
            </div>
            <div className="form-group">
              <label>Include Setup</label>
              <input
                type="checkbox"
                name="include_setup"
                checked={projectData.rawData.include_setup || false}
                onChange={(e) =>
                  setProjectData((prev) => ({
                    ...prev,
                    rawData: {
                      ...prev.rawData,
                      include_setup: e.target.checked,
                    },
                  }))
                }
              />
            </div>
            <div className="form-group">
              <label>Express Shipping</label>
              <input
                type="checkbox"
                name="express_shipping"
                checked={projectData.rawData.express_shipping || false}
                onChange={(e) =>
                  setProjectData((prev) => ({
                    ...prev,
                    rawData: {
                      ...prev.rawData,
                      express_shipping: e.target.checked,
                    },
                  }))
                }
              />
            </div>
            <div className="form-actions">
              <button className="btn btn-save" onClick={handleSave}>
                <i className="fas fa-save"></i> Save Changes
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default ProjectPlanningPanel;