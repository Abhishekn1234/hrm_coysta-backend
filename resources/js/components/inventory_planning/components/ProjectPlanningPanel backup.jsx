import React, { useState } from 'react';
import './Main.css';

const projectTabs = [
  { id: 'overview', label: 'Project Overview', icon: 'fas fa-globe' },
  { id: 'details', label: 'Project Details', icon: 'fas fa-info-circle' },
  { id: 'staff', label: 'Team Assignment', icon: 'fas fa-users' },
  { id: 'cost', label: 'Budget Planning', icon: 'fas fa-dollar-sign' },
  { id: 'timeline', label: 'Project Timeline', icon: 'fas fa-calendar-alt' },
  { id: 'inventory', label: 'Resources Planning', icon: 'fas fa-boxes' },
];

const ProjectPlanningPanel = ({ 
  selectedProject, 
  activeTab, 
  onTabChange,
  isCreatingNew,
  onSaveProject,
  onCancelProject
}) => {
  const [projectData, setProjectData] = useState(selectedProject);
  
  React.useEffect(() => {
    setProjectData(selectedProject);
  }, [selectedProject]);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setProjectData(prev => ({
      ...prev,
      [name]: value
    }));
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
      {/* Project Header */}
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
                placeholder="Project Title"
              />
            ) : (
              selectedProject.title
            )}
          </h2>
          <div className={`event-status ${isCreatingNew ? 'draft-status' : ''}`}>
            {isCreatingNew ? 'Draft' : selectedProject.status}
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
                `Client: ${selectedProject.client}`
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
                  placeholder="Project Manager"
                  className="new-event-input"
                />
              ) : (
                `Manager: ${selectedProject.manager}`
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
                `Date: ${selectedProject.date}`
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
        {/* Project Details Tab */}
        {activeTab === 'details' && (
          <div className="event-details-form">
            <div className="form-section">
              <div className="form-group">
                <label>Project Type</label>
                <select 
                  name="type" 
                  value={projectData.type || ''} 
                  onChange={handleInputChange}
                  className="form-control"
                >
                  <option value="">Select type</option>
                  <option value="Software">Software</option>
                  <option value="Construction">Construction</option>
                  <option value="Marketing">Marketing</option>
                  <option value="Research">Research</option>
                </select>
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
                <label>Progress (%)</label>
                <input
                  type="number"
                  name="progress"
                  value={projectData.progress}
                  onChange={handleInputChange}
                  min="0"
                  max="100"
                  className="form-control"
                />
              </div>
            </div>
            
            <div className="form-section">
              <div className="form-group">
                <label>Project Date</label>
                <input
                  type="date"
                  name="date"
                  value={projectData.date}
                  onChange={handleInputChange}
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
              
              <div className="form-group">
                <label>Project Manager</label>
                <input
                  type="text"
                  name="manager"
                  value={projectData.manager}
                  onChange={handleInputChange}
                  placeholder="Project manager"
                  className="form-control"
                />
              </div>
            </div>
            
            <div className="form-group">
              <label>Status</label>
              <select 
                name="status" 
                value={projectData.status} 
                onChange={handleInputChange}
                className="form-control"
              >
                <option value="Draft">Draft</option>
                <option value="Planning">Planning</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
            
            <div className="form-group">
              <label>Additional Notes</label>
              <textarea
                className="form-control"
                name="description"
                value={projectData.description || ''}
                onChange={handleInputChange}
                rows="4"
                placeholder="Enter project details..."
              ></textarea>
            </div>
            
            <div className="form-actions">
              <button className="btn btn-save" onClick={handleSave}>
                <i className="fas fa-save"></i> 
                {isCreatingNew ? 'Create Project' : 'Save Changes'}
              </button>
              <button className="btn btn-cancel" onClick={handleCancel}>
                <i className="fas fa-times"></i> Cancel
              </button>
            </div>
          </div>
        )}

        {/* Project Overview Tab */}
        {activeTab === 'overview' && (
          <div className="tab-content active" id="overview-tab">
            <div className="overview-stats">
              <div className="overview-stat">
                <div className="stat-label">Project Type</div>
                <div className="stat-value">
                  {isCreatingNew ? projectData.type || 'Not set' : selectedProject.type}
                </div>
              </div>
              <div className="overview-stat">
                <div className="stat-label">Progress</div>
                <div className="stat-value">
                  {isCreatingNew ? projectData.progress : selectedProject.progress}%
                </div>
              </div>
              <div className="overview-stat">
                <div className="stat-label">Manager</div>
                <div className="stat-value">
                  {isCreatingNew ? projectData.manager || 'Unassigned' : selectedProject.manager}
                </div>
              </div>
              <div className="overview-stat">
                <div className="stat-label">Budget</div>
                <div className="stat-value">$25,000</div>
              </div>
            </div>
            <div className="chart-container">
              <div className="chart-placeholder">
                <i className="fas fa-chart-line"></i>
                <h3>Project Progress & Milestones</h3>
                <div className="progress-bar-container">
                  <div 
                    className="progress-bar" 
                    style={{ 
                      width: `${isCreatingNew ? projectData.progress : selectedProject.progress}%` 
                    }}
                  ></div>
                </div>
                {isCreatingNew && (
                  <p className="draft-notice">
                    <i className="fas fa-info-circle"></i> 
                    Finish creating your project to see detailed insights
                  </p>
                )}
              </div>
            </div>
            <div className="overview-grid">
              <div className="overview-card">
                <h4>Upcoming Tasks</h4>
                <ul className="task-list">
                  <li>
                    <i className="fas fa-check-circle pending"></i>
                    <span>Complete project scoping</span>
                    <span>Tomorrow</span>
                  </li>
                  <li>
                    <i className="fas fa-check-circle pending"></i>
                    <span>Schedule team kickoff</span>
                    <span>In 3 days</span>
                  </li>
                  <li>
                    <i className="fas fa-check-circle completed"></i>
                    <span>Approve initial designs</span>
                    <span>Completed</span>
                  </li>
                </ul>
              </div>
              <div className="overview-card">
                <h4>Recent Activity</h4>
                <ul className="activity-list">
                  <li>
                    <i className="fas fa-comment"></i>
                    <div>
                      <span>Sarah updated project requirements</span>
                      <small>2 hours ago</small>
                    </div>
                  </li>
                  <li>
                    <i className="fas fa-file-invoice"></i>
                    <div>
                      <span>Budget updated to $25,000</span>
                      <small>Yesterday</small>
                    </div>
                  </li>
                  <li>
                    <i className="fas fa-user-plus"></i>
                    <div>
                      <span>Robert Davis assigned as lead</span>
                      <small>2 days ago</small>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        )}

        {/* Team Assignment Tab */}
        {activeTab === 'staff' && (
          <div className="tab-content active" id="staff-tab">
            <h3>Team Assignment</h3>
            {isCreatingNew ? (
              <div className="draft-content">
                <i className="fas fa-users"></i>
                <h4>Assign Team After Creating Project</h4>
                <p>Finish setting up your project to assign team members</p>
              </div>
            ) : (
              <>
                <p>Assign team members to project roles</p>
                <div className="staff-list">
                  <div className="staff-card">
                    <div className="staff-avatar">SJ</div>
                    <div className="staff-info">
                      <h4>Sarah Johnson</h4>
                      <p>Project Manager</p>
                      <span className="role-badge role-manager">Manager</span>
                    </div>
                    <div className="staff-actions">
                      <button className="btn btn-small">Edit</button>
                    </div>
                  </div>
                  <div className="staff-card">
                    <div className="staff-avatar">RD</div>
                    <div className="staff-info">
                      <h4>Robert Davis</h4>
                      <p>Technical Lead</p>
                      <span className="role-badge role-supervisor">Lead</span>
                    </div>
                    <div className="staff-actions">
                      <button className="btn btn-small">Edit</button>
                    </div>
                  </div>
                  <div className="staff-card">
                    <div className="staff-avatar">MJ</div>
                    <div className="staff-info">
                      <h4>Michael Johnson</h4>
                      <p>Developer</p>
                      <span className="role-badge role-technician">Contributor</span>
                    </div>
                    <div className="staff-actions">
                      <button className="btn btn-small">Edit</button>
                    </div>
                  </div>
                  <button className="btn btn-staff">
                    <i className="fas fa-plus"></i> Add Team Member
                  </button>
                </div>
              </>
            )}
          </div>
        )}

        {/* Budget Planning Tab */}
        {activeTab === 'cost' && (
          <div className="tab-content active" id="cost-tab">
            <h3>Budget Planning</h3>
            {isCreatingNew ? (
              <div className="draft-content">
                <i className="fas fa-money-bill-wave"></i>
                <h4>Set Budget After Creating Project</h4>
                <p>Complete project creation to plan your budget</p>
              </div>
            ) : (
              <>
                <p>Manage project finances and expenses</p>
                <div className="budget-summary">
                  <div className="budget-item">
                    <span>Software Licenses</span>
                    <span>$12,500</span>
                  </div>
                  <div className="budget-item">
                    <span>Contractor Fees</span>
                    <span>$8,200</span>
                  </div>
                  <div className="budget-item">
                    <span>Hardware</span>
                    <span>$3,500</span>
                  </div>
                  <div className="budget-item">
                    <span>Consulting</span>
                    <span>$2,800</span>
                  </div>
                  <div className="budget-total">
                    <span>Total Budget</span>
                    <span>$27,000</span>
                  </div>
                </div>
                <div className="chart-container">
                  <div className="chart-placeholder">
                    <i className="fas fa-pie-chart"></i>
                    <h3>Budget Allocation</h3>
                    <p>Visual breakdown of budget categories</p>
                  </div>
                </div>
              </>
            )}
          </div>
        )}

        {/* Project Timeline Tab */}
        {activeTab === 'timeline' && (
          <div className="tab-content active" id="timeline-tab">
            <h3>Project Timeline</h3>
            {isCreatingNew ? (
              <div className="draft-content">
                <i className="fas fa-hourglass-half"></i>
                <h4>Create Timeline After Project Setup</h4>
                <p>Finalize your project details to build the timeline</p>
              </div>
            ) : (
              <>
                <p>Schedule project activities and milestones</p>
                <div className="timeline">
                  <div className="timeline-event">
                    <div className="timeline-dot"></div>
                    <div className="timeline-content">
                      <h4>Project Kickoff</h4>
                      <p className="timeline-date">Jun 15, 2023</p>
                      <p>Initial team meeting and planning</p>
                    </div>
                  </div>
                  <div className="timeline-event">
                    <div className="timeline-dot"></div>
                    <div className="timeline-content">
                      <h4>Phase 1 Development</h4>
                      <p className="timeline-date">Jul 1, 2023</p>
                      <p>Core feature implementation</p>
                    </div>
                  </div>
                  <div className="timeline-event">
                    <div className="timeline-dot"></div>
                    <div className="timeline-content">
                      <h4>Midpoint Review</h4>
                      <p className="timeline-date">Aug 15, 2023</p>
                      <p>Stakeholder review and feedback</p>
                    </div>
                  </div>
                  <div className="timeline-event">
                    <div className="timeline-dot"></div>
                    <div className="timeline-content">
                      <h4>Final Delivery</h4>
                      <p className="timeline-date">Dec 15, 2023</p>
                      <p>Project completion and handover</p>
                    </div>
                  </div>
                </div>
              </>
            )}
          </div>
        )}

        {/* Resources Planning Tab */}
        {activeTab === 'inventory' && (
          <div className="tab-content active" id="inventory-tab">
            <h3>Resources Planning</h3>
            {isCreatingNew ? (
              <div className="draft-content">
                <i className="fas fa-boxes"></i>
                <h4>Plan Resources After Project Creation</h4>
                <p>Complete your project setup to allocate resources</p>
              </div>
            ) : (
              <>
                <p>Allocate equipment and materials</p>
                <div className="inventory-list">
                  <div className="inventory-item">
                    <span>Workstations</span>
                    <span>10 units</span>
                  </div>
                  <div className="inventory-item">
                    <span>Software Licenses</span>
                    <span>20 units</span>
                  </div>
                  <div className="inventory-item">
                    <span>Servers</span>
                    <span>5 units</span>
                  </div>
                  <div className="inventory-item">
                    <span>Testing Equipment</span>
                    <span>8 units</span>
                  </div>
                  <div className="inventory-item">
                    <span>Prototyping Materials</span>
                    <span>50 units</span>
                  </div>
                </div>
                <div className="inventory-actions">
                  <button className="btn btn-inventory">
                    <i className="fas fa-plus"></i> Add Resource
                  </button>
                  <button className="btn btn-secondary">
                    <i className="fas fa-file-export"></i> Export List
                  </button>
                </div>
              </>
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default ProjectPlanningPanel;