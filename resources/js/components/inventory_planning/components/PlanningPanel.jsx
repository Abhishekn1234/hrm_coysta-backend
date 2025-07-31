import React, { useState } from 'react';
import './Main.css';

const tabs = [
  { id: 'overview', label: 'Event Overview', icon: 'fas fa-globe' },
  { id: 'details', label: 'Event Details', icon: 'fas fa-info-circle' }, // Changed to Event Details
  { id: 'staff', label: 'Staff Assignment', icon: 'fas fa-users' },
  { id: 'cost', label: 'Budget Planning', icon: 'fas fa-dollar-sign' },
  { id: 'timeline', label: 'Event Timeline', icon: 'fas fa-calendar-alt' },
  { id: 'inventory', label: 'Resources Planning', icon: 'fas fa-boxes' },
];

const PlanningPanel = ({ 
  selectedEvent, 
  activeTab, 
  onTabChange,
  isCreatingNew,
  onSaveEvent,
  onCancelEvent
}) => {
  const [eventData, setEventData] = useState(selectedEvent);
  
  React.useEffect(() => {
    setEventData(selectedEvent);
  }, [selectedEvent]);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setEventData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSave = () => {
    onSaveEvent(eventData);
    if (isCreatingNew) {
      onTabChange('overview');
    }
  };

  const handleCancel = () => {
    onCancelEvent();
  };

  return (
    <div className="planning-panel">
      {/* Event Header */}
      <div className="event-detail-header">
        <div className="event-title-container">
          <h2>
            {isCreatingNew ? (
              <input
                type="text"
                name="title"
                value={eventData.title}
                onChange={handleInputChange}
                className="new-event-title-input"
                placeholder="Event Title"
              />
            ) : (
              selectedEvent.title
            )}
          </h2>
          <div className={`event-status ${isCreatingNew ? 'draft-status' : ''}`}>
            {isCreatingNew ? 'Draft' : selectedEvent.status}
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
                  value={eventData.client}
                  onChange={handleInputChange}
                  placeholder="Client Name"
                  className="new-event-input"
                />
              ) : (
                `Client: ${selectedEvent.client}`
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
                  value={eventData.manager}
                  onChange={handleInputChange}
                  placeholder="Event Manager"
                  className="new-event-input"
                />
              ) : (
                `Manager: ${selectedEvent.manager}`
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
                  value={eventData.date}
                  onChange={handleInputChange}
                  className="new-event-input"
                />
              ) : (
                `Date: ${selectedEvent.date}`
              )}
            </span>
          </div>
        </div>
      </div>

      {/* Tabs Navigation */}
      <div className="tabs">
        {tabs.map((tab) => (
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
        {/* Always show the form in Event Details tab */}
        {activeTab === 'details' && (
          <div className="event-details-form">
            <div className="form-section">
              <div className="form-group">
                <label>Event Type</label>
                <select 
                  name="type" 
                  value={eventData.type || ''} 
                  onChange={handleInputChange}
                  className="form-control"
                >
                  <option value="">Select type</option>
                  <option value="Conference">Conference</option>
                  <option value="Wedding">Wedding</option>
                  <option value="Concert">Concert</option>
                  <option value="Corporate">Corporate</option>
                </select>
              </div>
              
              <div className="form-group">
                <label>Event Title</label>
                <input
                  type="text"
                  name="title"
                  value={eventData.title}
                  onChange={handleInputChange}
                  placeholder="Event title"
                  className="form-control"
                />
              </div>
              
              <div className="form-group">
                <label>Progress (%)</label>
                <input
                  type="number"
                  name="progress"
                  value={eventData.progress}
                  onChange={handleInputChange}
                  min="0"
                  max="100"
                  className="form-control"
                />
              </div>
            </div>
            
            <div className="form-section">
              <div className="form-group">
                <label>Event Date</label>
                <input
                  type="date"
                  name="date"
                  value={eventData.date}
                  onChange={handleInputChange}
                  className="form-control"
                />
              </div>
              
              <div className="form-group">
                <label>Client</label>
                <input
                  type="text"
                  name="client"
                  value={eventData.client}
                  onChange={handleInputChange}
                  placeholder="Client name"
                  className="form-control"
                />
              </div>
              
              <div className="form-group">
                <label>Event Manager</label>
                <input
                  type="text"
                  name="manager"
                  value={eventData.manager}
                  onChange={handleInputChange}
                  placeholder="Event manager"
                  className="form-control"
                />
              </div>
            </div>
            
            <div className="form-group">
              <label>Status</label>
              <select 
                name="status" 
                value={eventData.status} 
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
                value={eventData.description || ''}
                onChange={handleInputChange}
                rows="4"
                placeholder="Enter event details..."
              ></textarea>
            </div>
            
            <div className="form-actions">
              <button className="btn btn-save" onClick={handleSave}>
                <i className="fas fa-save"></i> 
                {isCreatingNew ? 'Create Event' : 'Save Changes'}
              </button>
              <button className="btn btn-cancel" onClick={handleCancel}>
                <i className="fas fa-times"></i> Cancel
              </button>
            </div>
          </div>
        )}

        {activeTab === 'overview' && (
          <div className="tab-content active" id="overview-tab">
            <div className="overview-stats">
              <div className="overview-stat">
                <div className="stat-label">Event Type</div>
                <div className="stat-value">
                  {isCreatingNew ? eventData.type || 'Not set' : selectedEvent.type}
                </div>
              </div>
              <div className="overview-stat">
                <div className="stat-label">Progress</div>
                <div className="stat-value">
                  {isCreatingNew ? eventData.progress : selectedEvent.progress}%
                </div>
              </div>
              <div className="overview-stat">
                <div className="stat-label">Manager</div>
                <div className="stat-value">
                  {isCreatingNew ? eventData.manager || 'Unassigned' : selectedEvent.manager}
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
                <h3>Event Progress & Milestones</h3>
                <div className="progress-bar-container">
                  <div 
                    className="progress-bar" 
                    style={{ 
                      width: `${isCreatingNew ? eventData.progress : selectedEvent.progress}%` 
                    }}
                  ></div>
                </div>
                {isCreatingNew && (
                  <p className="draft-notice">
                    <i className="fas fa-info-circle"></i> 
                    Finish creating your event to see detailed insights
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
                    <span>Finalize venue booking</span>
                    <span>Tomorrow</span>
                  </li>
                  <li>
                    <i className="fas fa-check-circle pending"></i>
                    <span>Send invitations</span>
                    <span>In 3 days</span>
                  </li>
                  <li>
                    <i className="fas fa-check-circle completed"></i>
                    <span>Hire caterer</span>
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
                      <span>Sarah commented on the venue selection</span>
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
                      <span>Robert Davis added to team</span>
                      <small>2 days ago</small>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        )}

        {activeTab === 'staff' && (
          <div className="tab-content active" id="staff-tab">
            <h3>Staff Assignment</h3>
            {isCreatingNew ? (
              <div className="draft-content">
                <i className="fas fa-users"></i>
                <h4>Assign Staff After Creating Event</h4>
                <p>Finish setting up your event to assign team members</p>
              </div>
            ) : (
              <>
                <p>Assign team members to event roles</p>
                <div className="staff-list">
                  <div className="staff-card">
                    <div className="staff-avatar">SJ</div>
                    <div className="staff-info">
                      <h4>Sarah Johnson</h4>
                      <p>Event Manager</p>
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
                      <p>Logistics Coordinator</p>
                      <span className="role-badge role-supervisor">Supervisor</span>
                    </div>
                    <div className="staff-actions">
                      <button className="btn btn-small">Edit</button>
                    </div>
                  </div>
                  <div className="staff-card">
                    <div className="staff-avatar">MJ</div>
                    <div className="staff-info">
                      <h4>Michael Johnson</h4>
                      <p>Audio Technician</p>
                      <span className="role-badge role-technician">Technician</span>
                    </div>
                    <div className="staff-actions">
                      <button className="btn btn-small">Edit</button>
                    </div>
                  </div>
                  <button className="btn btn-staff">
                    <i className="fas fa-plus"></i> Add Staff Member
                  </button>
                </div>
              </>
            )}
          </div>
        )}

        {activeTab === 'cost' && (
          <div className="tab-content active" id="cost-tab">
            <h3>Budget Planning</h3>
            {isCreatingNew ? (
              <div className="draft-content">
                <i className="fas fa-money-bill-wave"></i>
                <h4>Set Budget After Creating Event</h4>
                <p>Complete event creation to plan your budget</p>
              </div>
            ) : (
              <>
                <p>Manage event finances and expenses</p>
                <div className="budget-summary">
                  <div className="budget-item">
                    <span>Venue Rental</span>
                    <span>$12,500</span>
                  </div>
                  <div className="budget-item">
                    <span>Catering</span>
                    <span>$8,200</span>
                  </div>
                  <div className="budget-item">
                    <span>Audio/Visual</span>
                    <span>$3,500</span>
                  </div>
                  <div className="budget-item">
                    <span>Marketing</span>
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

        {activeTab === 'timeline' && (
          <div className="tab-content active" id="timeline-tab">
            <h3>Event Timeline</h3>
            {isCreatingNew ? (
              <div className="draft-content">
                <i className="fas fa-hourglass-half"></i>
                <h4>Create Timeline After Event Setup</h4>
                <p>Finalize your event details to build the timeline</p>
              </div>
            ) : (
              <>
                <p>Schedule event activities and milestones</p>
                <div className="timeline">
                  <div className="timeline-event">
                    <div className="timeline-dot"></div>
                    <div className="timeline-content">
                      <h4>Setup Begins</h4>
                      <p className="timeline-date">Jun 15, 2023 - 8:00 AM</p>
                      <p>Venue preparation and equipment setup</p>
                    </div>
                  </div>
                  <div className="timeline-event">
                    <div className="timeline-dot"></div>
                    <div className="timeline-content">
                      <h4>Guest Arrival</h4>
                      <p className="timeline-date">Jun 15, 2023 - 10:00 AM</p>
                      <p>Registration opens, welcome drinks served</p>
                    </div>
                  </div>
                  <div className="timeline-event">
                    <div className="timeline-dot"></div>
                    <div className="timeline-content">
                      <h4>Opening Keynote</h4>
                      <p className="timeline-date">Jun 15, 2023 - 11:00 AM</p>
                      <p>Keynote speech by industry leader</p>
                    </div>
                  </div>
                  <div className="timeline-event">
                    <div className="timeline-dot"></div>
                    <div className="timeline-content">
                      <h4>Lunch Break</h4>
                      <p className="timeline-date">Jun 15, 2023 - 1:00 PM</p>
                      <p>Buffet lunch with networking opportunities</p>
                    </div>
                  </div>
                </div>
              </>
            )}
          </div>
        )}

        {activeTab === 'inventory' && (
          <div className="tab-content active" id="inventory-tab">
            <h3>Resources Planning</h3>
            {isCreatingNew ? (
              <div className="draft-content">
                <i className="fas fa-boxes"></i>
                <h4>Plan Resources After Event Creation</h4>
                <p>Complete your event setup to allocate resources</p>
              </div>
            ) : (
              <>
                <p>Allocate equipment and materials</p>
                <div className="inventory-list">
                  <div className="inventory-item">
                    <span>Audio Equipment</span>
                    <span>15 units</span>
                  </div>
                  <div className="inventory-item">
                    <span>Chairs</span>
                    <span>250 units</span>
                  </div>
                  <div className="inventory-item">
                    <span>Tables</span>
                    <span>40 units</span>
                  </div>
                  <div className="inventory-item">
                    <span>Lighting Equipment</span>
                    <span>20 units</span>
                  </div>
                  <div className="inventory-item">
                    <span>Projectors</span>
                    <span>5 units</span>
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

export default PlanningPanel;