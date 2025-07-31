import React, { useState } from 'react';
import './Main.css';

const EventItem = ({ event, isActive, isNew, onSelect }) => {
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);

  const handleDropdownClick = (e) => {
    e.stopPropagation();
    setIsDropdownOpen(!isDropdownOpen);
  };

  return (
    <div 
      className={`event-item ${isActive ? 'active' : ''} ${isNew ? 'new-event' : ''}`} 
      onClick={() => onSelect(event)}
    >
      {isNew && (
        <div className="new-event-badge">
          <i className="fas fa-star"></i> NEW
        </div>
      )}
      
      <span className="event-type">{event.type || 'New-Project'}</span>
      <div className="event-title">
        <h3>{event.title}</h3>
        <span className={`event-badge ${isNew ? 'draft-badge' : ''}`}>
          {isNew ? 'Draft' : event.status}
        </span>
      </div>
      <div className="event-details">
        <span>
          <i className="fas fa-user"></i> {event.manager || 'Unassigned'}
        </span>
        <span>
          <i className="fas fa-calendar"></i> {event.date}
        </span>
      </div>
      <div className="event-progress">
        <div className="progress-bar" style={{ width: `${event.progress}%` }}></div>
      </div>
      <div className="event-footer">
        <span>{event.client || 'No client'}</span>
        <span>{event.progress}% Complete</span>
      </div>
      <div className="event-actions">
        <button 
          className="action-btn" 
          onClick={(e) => {
            e.stopPropagation();
            handleDropdownClick(e);
          }}
        >
          <i className="fas fa-ellipsis-v"></i>
        </button>
        <div className={`dropdown-menu ${isDropdownOpen ? 'show' : ''}`}>
          <div className="dropdown-item">
            <i className="fas fa-edit"></i> Edit Event
          </div>
        </div>
      </div>
    </div>
  );
};

const EventPanel = ({ 
  events, 
  selectedEvent, 
  onEventSelect, 
  isCreatingNew 
}) => {
  return (
    <div className="event-panel">
      <div className="panel-header">
        <h2>
          <i className="fas fa-calendar-check"></i> Active Projects
          {isCreatingNew && (
            <span className="creating-indicator">
              <i className="fas fa-pencil-alt"></i> Creating new event...
            </span>
          )}
        </h2>
        <p className="subtitle">Select an project to view details</p>
      </div>
      <div className="event-list">
        {events.map((event) => (
          <EventItem
            key={event.id}
            event={event}
            isActive={selectedEvent?.id === event.id}
            isNew={event.isNew}
            onSelect={onEventSelect}
          />
        ))}
      </div>
    </div>
  );
};

export default EventPanel;