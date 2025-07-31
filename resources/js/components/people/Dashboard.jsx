import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Users, UserCheck, UserX, Gift } from 'lucide-react';

export default function Dashboard() {
  const [counts, setCounts] = useState({
    total: 0,
    active: 0,
    inactive: 0,
    monthly: 0
  });
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchCounts = async () => {
      try {
        const res = await axios.get('/api/staff/counts');
        setCounts((prev) => ({
          ...prev,
          total: res.data.total_staff || 0,
          active: res.data.active_staff || 0,
          inactive: res.data.inactive_staff || 0
        }));
      } catch (err) {
        console.error('Error fetching staff counts:', err);
        setError('Failed to load staff counts');
      }
    };

    const fetchMonthly = async () => {
      try {
        const res = await axios.get('/api/staff/monthly');
        const latestMonth = res.data?.[res.data.length - 1]?.count || 0;
        setCounts((prev) => ({
          ...prev,
          monthly: latestMonth
        }));
      } catch (err) {
        console.error('Error fetching monthly staff:', err);
        setError('Failed to load monthly staff');
      }
    };

    fetchCounts();
    fetchMonthly();
  }, []);

  const cards = [
    {
      title: 'Total Staff',
      value: counts.total,
      icon: <Users size={20} color="#1d4ed8" />,
      bg: '#e0edff'
    },
    {
      title: 'Active',
      value: counts.active,
      icon: <UserCheck size={20} color="#16a34a" />,
      bg: '#e6f6ec'
    },
    {
      title: 'Inactive',
      value: counts.inactive,
      icon: <UserX size={20} color="#dc2626" />,
      bg: '#fdeaea'
    },
    {
      title: 'Monthly Staff',
      value: counts.monthly,
      icon: <Gift size={20} color="#9333ea" />,
      bg: '#f3e8ff'
    }
  ];

  const containerStyle = {
    display: 'flex',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
    gap: '24px',
    marginTop: '32px',
    padding: '0 16px',
    backgroundColor: '#f9fafb' // Light background to ensure visibility
  };

  const cardStyle = {
    backgroundColor: '#ffffff',
    borderRadius: '12px',
    boxShadow: '0 6px 12px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06)',
    padding: '24px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'flex-start',
    flex: '1 1 calc(25% - 24px)',
    minWidth: '240px',
    maxWidth: '100%',
    height: '120px',
    transition: 'transform 0.2s ease, box-shadow 0.2s ease' // Transition for CSS hover
  };

  const iconWrapperStyle = (bg) => ({
    backgroundColor: bg || '#f3f4f6', // Fallback background
    borderRadius: '50%',
    width: '50px',
    height: '50px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: '20px',
    flexShrink: 0,
    boxShadow: '0 2px 4px rgba(0, 0, 0, 0.05)'
  });

  const valueStyle = {
    fontWeight: 700,
    fontSize: '24px',
    color: '#111827',
    lineHeight: '1.3',
    letterSpacing: '-0.025em'
  };

  const labelStyle = {
    fontSize: '15px',
    color: '#4b5563',
    marginTop: '4px',
    fontWeight: 500
  };

  return (
    <>
      {error && <div style={{ color: '#dc2626', padding: '16px', textAlign: 'center' }}>{error}</div>}
      <style>
        {`
          .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15), 0 3px 6px rgba(0, 0, 0, 0.08);
          }
        `}
      </style>
      <div style={containerStyle}>
        {cards.map((card, idx) => (
          <div key={idx} className="dashboard-card" style={cardStyle}>
            <div style={iconWrapperStyle(card.bg)}>{card.icon}</div>
            <div>
              <div style={valueStyle}>{card.value}</div>
              <div style={labelStyle}>{card.title}</div>
            </div>
          </div>
        ))}
      </div>
    </>
  );
}