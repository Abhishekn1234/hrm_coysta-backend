import React, { useState } from 'react';
import './Main.css';

const ProjectItem = ({ project, isActive, isNew, onSelect }) => {
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);

  const handleDropdownClick = (e) => {
    e.stopPropagation();
    setIsDropdownOpen(!isDropdownOpen);
  };

  return (
    <div 
      className={`event-item ${isActive ? 'active' : ''} ${isNew ? 'new-event' : ''}`} 
      onClick={() => onSelect(project)}
    >
      {isNew && (
        <div className="new-event-badge">
          <i className="fas fa-star"></i> NEW
        </div>
      )}
      
      <span className="event-type">{project.type || 'New Project'}</span>
      <div className="event-title">
        <h3>{project.title}</h3>
        <span className={`event-badge ${isNew ? 'draft-badge' : ''}`}>
          {isNew ? 'Draft' : project.status}
        </span>
      </div>
      <div className="event-details">
        <span>
          <i className="fas fa-user"></i> {project.manager || 'Unassigned'}
        </span>
        <span>
          <i className="fas fa-calendar"></i> {project.date}
        </span>
      </div>
      <div className="event-progress">
        <div className="progress-bar" style={{ width: `${project.progress}%` }}></div>
      </div>
      <div className="event-footer">
        <span>{project.client || 'No client'}</span>
        <span>{project.progress}% Complete</span>
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
            <i className="fas fa-edit"></i> Edit Project
          </div>
        </div>
      </div>
    </div>
  );
};

const ProjectPanel = ({ 
  projects, 
  selectedProject, 
  onProjectSelect, 
  isCreatingNew 
}) => {
  return (
    <div className="event-panel">
      <div className="panel-header">
        <h2>
          <i className="fas fa-project-diagram"></i> Active Quotations
          {isCreatingNew && (
            <span className="creating-indicator">
              <i className="fas fa-pencil-alt"></i> Creating new project...
            </span>
          )}
        </h2>
        <p className="subtitle">Select a Quotation to Approve and Create Project</p>
      </div>
      <div className="event-list">
        {projects.map((project) => (
          <ProjectItem
            key={project.id}
            project={project}
            isActive={selectedProject?.id === project.id}
            isNew={project.isNew}
            onSelect={onProjectSelect}
          />
        ))}
      </div>
    </div>
  );
};

export default ProjectPanel;