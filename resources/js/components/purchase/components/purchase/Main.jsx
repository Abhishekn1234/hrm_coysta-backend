import React from 'react'
import DashboardHeader from './Dashboard/DashboardHeader'
import DashboardStats from './Dashboard/DashboardStats'
import QuickActionsDashboard from './Dashboard/QuickActionsDashboard'
import ProcurementTabs from './ProcurementTabs/ProcurementTabs'

const Main = () => {
  return (
    <div className='main-container'>
       <style>
        {`
          :root {
            --primary: #3c8dbc;
            --secondary: #367fa9;
            --success: #00a65a;
            --warning: #f39c12;
            --danger: #dd4b39;
            --info: #00c0ef;
            --light-bg: #f8f9fc;
          }
          
          body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #444;
          }
          
          .main-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 20px;
          }
        `}
      </style>
      <DashboardHeader />
      <DashboardStats />
      <QuickActionsDashboard />
      <ProcurementTabs />
    </div>
  )
}

export default Main