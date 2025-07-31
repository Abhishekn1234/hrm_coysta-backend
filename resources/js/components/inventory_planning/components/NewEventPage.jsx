import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

const NewEventPage = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    title: '',
    type: 'Conference',
    status: 'Planning',
    manager: '',
    date: '',
    client: '',
    progress: 0
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const newEvent = {
      ...formData,
      id: Date.now(),
      progress: parseInt(formData.progress)
    };
    
    // Save to localStorage and close tab
    const events = JSON.parse(localStorage.getItem('events')) || [];
    localStorage.setItem('events', JSON.stringify([...events, newEvent]));
    window.close();
  };

  return (
    <div className="new-event-page">
      <h1><i className="fas fa-plus"></i> Create New Event</h1>
      
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Event Title</label>
          <input 
            type="text" 
            name="title"
            value={formData.title}
            onChange={handleChange}
            required
          />
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Event Type</label>
            <select name="type" value={formData.type} onChange={handleChange}>
              <option value="Conference">Conference</option>
              <option value="Wedding">Wedding</option>
              <option value="Seminar">Seminar</option>
              <option value="Trade Show">Trade Show</option>
            </select>
          </div>

          <div className="form-group">
            <label>Status</label>
            <select name="status" value={formData.status} onChange={handleChange}>
              <option value="Planning">Planning</option>
              <option value="In Progress">In Progress</option>
              <option value="Completed">Completed</option>
            </select>
          </div>
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Manager</label>
            <input 
              type="text" 
              name="manager"
              value={formData.manager}
              onChange={handleChange}
              required
            />
          </div>

          <div className="form-group">
            <label>Date</label>
            <input 
              type="text" 
              name="date"
              value={formData.date}
              onChange={handleChange}
              placeholder="MMM DD, YYYY"
              required
            />
          </div>
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Client</label>
            <input 
              type="text" 
              name="client"
              value={formData.client}
              onChange={handleChange}
              required
            />
          </div>

          <div className="form-group">
            <label>Progress (%)</label>
            <input 
              type="number" 
              name="progress"
              value={formData.progress}
              onChange={handleChange}
              min="0"
              max="100"
              required
            />
          </div>
        </div>

        <div className="form-buttons">
          <button type="button" className="btn btn-secondary" onClick={() => window.close()}>
            Cancel
          </button>
          <button type="submit" className="btn btn-primary">
            Create Event
          </button>
        </div>
      </form>
    </div>
  );
};

export default NewEventPage;