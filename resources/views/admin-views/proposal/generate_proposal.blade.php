<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $proposal->proposal_title }} - Cosysta</title>
        <style>
            @media print {
                @page {
                    margin: 0;
                }
            }

            @media print {
                .header {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100px;
                    background-color: #fff;
                    padding: 0;
                    margin: 0;
                    border-bottom: 1px solid #ccc;
                    z-index: 999;
                }

                .footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                    height: 30px;
                    background-color: #fff;
                    padding: 0;
                    margin: 0;
                    border-top: 1px solid #ccc;
                    font-size: 12px;
                    text-align: center;
                    z-index: 999;
                }

                .page-content {
                    margin-top: 10px;
                    margin-bottom: 20px;
                }

                .page {
                    page-break-after: always;
                    position: relative;
                    overflow: hidden;
                }
        
                .page {
                    width: 21cm;
                    height: 29.7cm !important;
                    margin: 0px !important;
                    padding: 3cm 2cm 2cm 2cm;
                    background: transparent;
                    border-radius: 1px;
                    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
                    position: relative;
                    overflow: hidden;
                    page-break-after: none;
                    background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" opacity="0.05"><circle cx="50" cy="50" r="40" stroke="%232563eb" fill="none"/></svg>');
                }
            }

            :root {
                --primary: #2563eb;
                --secondary: #1e3a8a;
                --accent: #7c3aed;
                --light: #f8fafc;
                --dark: #1e293b;
                --text: #475569;
            }
    
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Inter', system-ui, sans-serif;
            }
    
            body {
                background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
                color: var(--dark);
                line-height: 1.8;
            }
    
            /* Page Layout */
            .page {
                width: 21cm;
                height: 29.7cm;
                margin: 20px auto;
                padding: 2cm 2cm 2cm;
                background: white;
                border-radius: 16px;
                box-shadow: 0 12px 24px rgba(0,0,0,0.1);
                position: relative;
                overflow: hidden;
                page-break-after: always;
                background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" opacity="0.05"><circle cx="50" cy="50" r="40" stroke="%232563eb" fill="none"/></svg>');
            }
    
            /* Header Styles */
            .header {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                background: var(--primary);
                padding: 1rem 3rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                z-index: 100;
            }
    
            .logo {
                width: 180px;
                filter: brightness(0) invert(1);
            }
    
            .nav-dots {
                display: flex;
                gap: 12px;
            }
    
            .nav-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: rgba(255,255,255,0.3);
                transition: all 0.3s ease;
            }
    
            .nav-dot.active {
                background: white;
                transform: scale(1.2);
            }
    
            /* Cover Page */
            .cover {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                background: linear-gradient(135deg, var(--secondary), var(--primary));
                color: white;
                padding: 4rem 2rem;
            }
    
            .cover::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(255,255,255,0.1) 10%, transparent 60%);
                transform: rotate(45deg);
            }
    
            .cover h1 {
                font-size: 4rem;
                font-weight: 900;
                letter-spacing: 4px;
                margin-bottom: 1.5rem;
                position: relative;
                z-index: 2;
                text-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }
    
            .cover h2 {
                font-size: 1.8rem;
                font-weight: 400;
                color: rgba(255,255,255,0.9);
                max-width: 600px;
                margin: 0 auto 3rem;
                line-height: 1.6;
                z-index: 2;
                position: relative;
            }
    
            .cover-image {
                width: 400px;
                height: 250px;
                border-radius: 16px;
                object-fit: cover;
                box-shadow: 0 8px 24px rgba(0,0,0,0.2);
                margin-top: 2rem;
                border: 2px solid rgba(255,255,255,0.1);
                z-index: 2;
            }
    
            /* About Section */
            .about-section {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                align-items: center;
                margin: 3rem 0;
            }
    
            .about-image {
                width: 100%;
                height: 400px;
                border-radius: 12px;
                object-fit: cover;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
    
            .about-content {
                padding: 2rem;
                background: var(--light);
                border-radius: 12px;
                border-left: 4px solid var(--primary);
            }
    
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 2rem;
                margin: 2rem 0;
            }
    
            .stat-card {
                text-align: center;
                padding: 1.5rem;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }
    
            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--primary);
                margin-bottom: 0.5rem;
            }
    
            /* Team Section */
            .team-section {
                margin: 3rem 0;
            }
    
            .team-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 2rem;
            }
    
            .team-member {
                position: relative;
                overflow: hidden;
                border-radius: 12px;
                transition: transform 0.3s ease;
                background: white;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
    
            .team-member:hover {
                transform: translateY(-8px);
            }
    
            .member-image {
                width: 100%;
                height: 300px;
                object-fit: cover;
                border-bottom: 3px solid var(--primary);
            }
    
            .member-info {
                padding: 1.5rem;
                text-align: center;
            }
    
            .member-social {
                display: flex;
                justify-content: center;
                gap: 1rem;
                margin-top: 1rem;
            }
    
            .social-icon {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: var(--light);
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }
    
            .social-icon:hover {
                background: var(--primary);
                color: white;
            }
    
            /* Contact Section */
            .contact-section {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                margin: 3rem 0;
            }
    
            .contact-map {
                height: 400px;
                background: var(--light);
                border-radius: 12px;
                overflow: hidden;
                position: relative;
            }
    
            .map-placeholder {
                width: 100%;
                height: 100%;
                background: #ddd;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--text);
            }
    
            .contact-info {
                padding: 2rem;
                background: var(--light);
                border-radius: 12px;
            }
    
            .info-item {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin: 1.5rem 0;
                padding: 1rem;
                background: white;
                border-radius: 8px;
            }
    
            .info-icon {
                width: 40px;
                height: 40px;
                background: var(--primary);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
            }
    
            /* Footer */
            .page-footer {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 2rem 3rem;
                background: var(--primary);
                color: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
    
            @media print {
                .page {
                    margin: 0;
                    box-shadow: none;
                    border-radius: 0;
                }
                
                
            }
        </style>
    </head>
    
    <body>
        <div class="page cover">
            <div class="header">
                 <img src="https://xianinfotech.in/TaskMaster/storage/app/white.png" alt="White Image">
                <div class="nav-dots">
                    <div class="nav-dot active"></div>
                    <div class="nav-dot"></div>
                    <div class="nav-dot"></div>
                    <div class="nav-dot"></div>
                </div>
            </div>
            
            <h1>{{ $proposal->proposal_title }}</h1>
            <h2>Empowering Your Business Through Innovative Technology Solutions</h2>
            <img src="{{ asset('storage/app/tech.webp') }}" class="cover-image" alt="Tech Cover">
        </div>

        <div class="page">
            <div class="header">
               <img src="https://xianinfotech.in/TaskMaster/storage/app/white.png" alt="White Image">
                <div class="nav-dots">
                    <div class="nav-dot"></div>
                    <div class="nav-dot active"></div>
                    <div class="nav-dot"></div>
                    <div class="nav-dot"></div>
                </div>
            </div>
        
            <div class="about-section">
                <img src="{{ asset('storage/app/about.webp') }}" class="about-image" alt="About Us">
                <div class="about-content">
                    <h2>About Cosysta</h2>
                    <p>We are a premier software development agency focused on delivering transformative digital solutions. With a perfect blend of technical expertise and industry knowledge, we help businesses unlock their full potential through technology.</p>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">15+</div>
                            <p>Years Experience</p>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">250+</div>
                            <p>Projects Delivered</p>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">98%</div>
                            <p>Client Satisfaction</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 1rem; width: 50%; margin-left: auto;">
                <div class="page-footer">
                    <p>© 2025 Cosysta Technologies. All rights reserved.</p>
                    <p>www.cosysta.com</p>
                </div>
            </div>
        </div>
   <?php 

$content = $proposal->proposal_description;

// Split by manual page break marker ###
$manualPages = preg_split('/###/', $content);

$pageStart = '
    <div class="page" style="padding-top: 20px; padding-bottom: 20px; position: relative;">
        <div class="header" style="padding-bottom: 10px; position: absolute; top: 0; left: 0; right: 0;">
            <img src="https://xianinfotech.in/TaskMaster/storage/app/white.png" class="logo" alt="Cosysta Logo">
            <div class="nav-dots">
                <div class="nav-dot"></div>
                <div class="nav-dot"></div>
                <div class="nav-dot active"></div>
                <div class="nav-dot"></div>
            </div>
        </div>
        <div class="page-content" style="min-height: 900px; padding-top: 85px; padding-bottom: 85px;">
';

$pageEnd = '
        </div>
        <div style="margin-top: 1rem; width: 50%; margin-left: auto; padding-top: 10px;">
            <div class="page-footer" style="padding-top: 10px; padding-bottom: 10px; position: absolute; bottom: 0; left: 0; right: 0;">
                <p>© 2025 Cosysta Technologies. All rights reserved.</p>
                <p>www.cosysta.com</p>
            </div>
        </div>
    </div>
';

$output = '';

// Just create a full page for each split
foreach ($manualPages as $manualPageContent) {
    $manualPageContent = trim($manualPageContent); // Remove unwanted spaces
    if (!empty($manualPageContent)) {
        $output .= $pageStart . $manualPageContent . $pageEnd;
    }
}

// Final Output
echo $output;

?>


        <style>
            .pricing-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 0.9em;
            }
        
            .pricing-table th,
            .pricing-table td {
                padding: 0.6rem;
                border: 1px solid #e2e8f0;
                line-height: 1.4;
            }
        
            .pricing-table th {
                background: var(--primary);
                color: white;
                font-weight: 600;
                white-space: nowrap;
            }
        
            .pricing-table tr:nth-child(even) {
                background: var(--light);
            }
        
            .total-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 0.95em;
            }
        
            .total-table td {
                padding: 0.6rem;
                border-bottom: 1px solid #e2e8f0;
            }
        
            .terms-section ol {
                column-count: 2;
                column-gap: 3rem;
            }
        </style>

        <div class="page">
            <div class="header">
                <img src="https://xianinfotech.in/TaskMaster/storage/app/white.png" class="logo" alt="Cosysta Logo">
                <div class="nav-dots">
                    <div class="nav-dot"></div>
                    <div class="nav-dot"></div>
                    <div class="nav-dot"></div>
                    <div class="nav-dot active"></div>
                </div>
            </div>
        
            <div class="contact-section">
                <div>
                    <img src="https://cosysta.com/wp-content/uploads/2024/12/pretty-teacher-posing-with-book-white_1-removebg-preview-1.png" width="300px;"
                </div>
                
                </div>
                <div class="contact-info">
                    <h2>Get In Touch</h2>
                    <div class="info-item">
                        <div class="info-icon">📍</div>
                        <div>
                            <h4>Headquarters</h4>
                            <p>Cosysta 65/1095A,Kassim Building, Sebastian Road, Kaloor-682017 Ernakulam, Kerala</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📞</div>
                        <div>
                            <h4>Contact Number</h4>
                            <p>+91 81299 35578</p>
                        </div>
                    </div>
                </div>
            </div>
        
            <div style="margin-top: 1rem; width: 50%; margin-left: auto;">
                <div class="page-footer">
                    <p>© 2025 Cosysta Technologies. All rights reserved.</p>
                    <p>www.cosysta.com</p>
                </div>
            </div>
        </div>
    </body>
</html>