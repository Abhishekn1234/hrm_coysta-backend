<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Offer Letter - Cosysta</title>
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

            .page {
                width: 21cm;
                height: 29.7cm;
                margin: 20px auto;
                padding: 3cm 2cm 2cm 2cm;
                background: white;
                border-radius: 16px;
                box-shadow: 0 12px 24px rgba(0,0,0,0.1);
                position: relative;
                overflow: hidden;
                page-break-after: always;
                background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" opacity="0.05"><circle cx="50" cy="50" r="40" stroke="%232563eb" fill="none"/></svg>');
            }

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
                height: 240px;
                border-radius: 16px;
                object-fit: cover;
                box-shadow: 0 8px 24px rgba(0,0,0,0.2);
                margin-top: 0rem;
                border: 2px solid rgba(255,255,255,0.1);
                z-index: 2;
            }

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

            .page-footer {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: .5rem .5rem;
                background: var(--primary);
                color: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            header, footer {
                height: 50px;
                margin: 0;
                padding: 0;
            }

            @page {
                size: A4;
                margin: 0;
            }

            .cover-page header {
                padding: 0px !important;
                margin: 0;
            }

            .cover-page {
                padding: 20px;
                height: 100vh;
                box-sizing: border-box;
            }

            .cover-page {
                width: 210mm;
                height: 297mm;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 20mm;
                box-sizing: border-box;
            }

            .cover-page header {
                padding: 5mm 0;
            }

            @media print {
                .cover-page {
                    page-break-before: always;
                    height: 297mm;
                }
            }
        </style>

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
    </head>
    
    <body>
        <style>
            @media print {
                button {
                    display: none;
                }
            }
        </style>
        <button onclick="window.print()" style="color: #fff;background-color: #377dff;border-color: #377dff;padding:10px;margin:50px;" class="">Print/Download</button>
        
        <div class="page">
            <div class="header">
                <img src="https://xianinfotech.in/TaskMaster/storage/app/white.png" alt="White Image">
                <div class="nav-dots">
                    <div class="nav-dot active"></div>
                    <div class="nav-dot"></div>
                    <div class="nav-dot"></div>
                </div>
            </div>

            <div style="">
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <div>
                        <h2 style="color: var(--primary);">OFFER OF EMPLOYMENT</h2>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0;">Date: {{date('d M Y')}}</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="info-card">
                        <h4>Cosysta Technologies</h4>
                        <p>Cosysta 65/1095A,Kassim Building, Sebastian Road, Kaloor-682017 Ernakulam, Kerala</p>
                    </div>
                    <div class="info-card">
                        <h4>To:</h4>
                        <p>{{ $staff->name }}<br>
                        {!! $staff->address !!}</p>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 1rem;">
                <div class="info-card">
                    <p>
                        <?php $name = explode(" ",$staff->name); ?>
                        Dear {{ $name[0] }},<br>
                        We are pleased to offer you the position of <b><i>{{ $staff->designation }}</i></b> at Cosysta (Xianinfotech LLP)
                    </p>
                </div>
            </div>

            <div style="margin-top: 1rem;"> 
                <h4>Position Details</h4>
                <table class="pricing-table">
                    <tr>
                        <td style="width: 30%">Designation</td>
                        <td style="width: 70%">{{ $staff->designation }}</td>
                    </tr>
                    
                    <tr>
                        <td style="width: 30%">Reporting Manager</td>
                        
                        @php($reports_to=\App\User::where(['status' => '1','id' => $staff->reports_to])->first())
                        <?php
                            if($reports_to) {
                                $reports_to_name = $reports_to->name . ' ( ' . $reports_to->user_type . ' )';
                            } else {
                                $reports_to_name = "None";
                            }
                        ?>
                        <td style="width: 70%">{{$reports_to_name}}</td>
                    </tr>
                    
                    <tr>
                        <td style="width: 30%">Work Location</td>
                        <td style="width: 70%">{{ $staff->work_location }}</td>
                    </tr>
                    
                    <tr>
                        <td style="width: 30%">Employment Type</td>
                        <td style="width: 70%">{{ $staff->employment_type }}</td>
                    </tr>
                    
                    <tr>
                        <td style="width: 30%">Joining Date</td>
                        <td style="width: 70%">{{date('d M Y',strtotime($staff->join_date))}}</td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-top: 1rem;margin-bottom: 2rem;"> 
                <h4>Compensation Package</h4>
                <table class="pricing-table">
                    <tr>
                        <td style="width: 30%">Annual CTC</td>
                        <td style="width: 70%">{{ $staff->annual_ctc }}</td>
                    </tr>
                    
                    <tr>
                        <td style="width: 30%">Monthly Breakdown</td>
                        <td style="width: 70%">
                            Basic Salary : {{ $staff->basic_salary }} ₹<br>
                            HRA : {{ $staff->hra }} ₹<br>
                            Special Allowances : {{ $staff->special_allowances }} ₹
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="width: 30%">Probation Period</td>
                        <td style="width: 70%">{{ $staff->probation_period }}</td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-top: 1rem; width: 50%; margin-left: auto;">
                <div class="page-footer">
                    <p>© 2029 Cosysta Technologies. All rights reserved.</p>
                    <p>www.cosysta.com</p>
                </div>
            </div>
        </div>
        
        
        <?php
$content = $letters->terms_and_conditions;

$pageStart = '
    <div class="page" style="padding-top: 20px; padding-bottom: 20px; position: relative;">
        <div class="header" style="padding-bottom: 10px; position: absolute; top: 0; left: 0; right: 0;">
            <img src="https://xianinfotech.in/TaskMaster/storage/app/white.png" class="logo" alt="Cosysta Logo">
            <div class="nav-dots">
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
                <p>© 2029 Cosysta Technologies. All rights reserved.</p>
                <p>www.cosysta.com</p>
            </div>
        </div>
    </div>
';

$output = '';
$currentPageContent = '';
$maxHeight = 1030; // Max content height per page in pixels
$currentHeight = 0;
$lastWasTable = false;

$pattern = '/(<table.*?<\/table>|<h[1-6].*?<\/h[1-6]>|<strong>.*?<\/strong>|<hr>|<[^>]+>|[^<]+)/is';
preg_match_all($pattern, $content, $matches);

foreach ($matches[0] as $chunk) {
    $chunkHeight = 0;

    // ✅ Handle manual page break using <hr>
    if (strpos($chunk, '<hr>') !== false) {
        if (!empty($currentPageContent)) {
            $output .= $pageStart . $currentPageContent . $pageEnd;
            $currentPageContent = '';
            $currentHeight = 0;
        }
        continue;
    }

    // ✅ Table Handling
    if (strpos($chunk, '<table') !== false) {
        preg_match_all('/<tr.*?<\/tr>/is', $chunk, $rows);
        $rowCount = count($rows[0]);
        $cellHeight = 24; // Approximate cell height in pixels
        $chunkHeight = $rowCount * $cellHeight + 20; // Add padding

        // 🚨 If the table is at the top of a new page, push it to the next page
        if (empty($currentPageContent)) {
            if ($chunkHeight > $maxHeight) {
                $output .= $pageStart . $chunk . $pageEnd;
                $currentPageContent = '';
                $currentHeight = 0;
                continue;
            } else {
                // Try adding a placeholder line to prevent table from being alone
                $currentPageContent .= '<div style="height: 20px;"></div>';
                $currentHeight += 20;
            }
        }

        // 🚨 If the table won't fit, push to next page
        if ($currentHeight + $chunkHeight > $maxHeight) {
            $output .= $pageStart . $currentPageContent . $pageEnd;
            $currentPageContent = $chunk;
            $currentHeight = $chunkHeight;
        } else {
            $currentPageContent .= $chunk;
            $currentHeight += $chunkHeight;
        }

        $lastWasTable = true;
        continue;
    }

    // ✅ Heading or Strong Tag Handling
    if (preg_match('/<h[1-6]>|<strong>/', $chunk)) {
        $chunkHeight = 40; // Approximate height for headings

        // 🚨 If heading appears at the bottom, move to next page
        if ($currentHeight + $chunkHeight > $maxHeight) {
            if (!empty($currentPageContent)) {
                $output .= $pageStart . $currentPageContent . $pageEnd;
            }
            $currentPageContent = $chunk;
            $currentHeight = $chunkHeight;
        } else {
            $currentPageContent .= $chunk;
            $currentHeight += $chunkHeight;
        }
        continue;
    }

    // ✅ Regular Content Handling
    $chunkHeight = strlen(strip_tags($chunk)) * 0.8; // Regular text height approximation

    if ($currentHeight + $chunkHeight > $maxHeight) {
        $output .= $pageStart . $currentPageContent . $pageEnd;
        $currentPageContent = $chunk;
        $currentHeight = $chunkHeight;
    } else {
        $currentPageContent .= $chunk;
        $currentHeight += $chunkHeight;
    }

    $lastWasTable = false;
}

// ✅ Add any remaining content to the last page
if (!empty($currentPageContent)) {
    $output .= $pageStart . $currentPageContent . $pageEnd;
}

echo $output;
?>
            
        <div class="page">
            <div class="header">
                <img src="https://xianinfotech.in/TaskMaster/storage/app/white.png" alt="White Image">
                <div class="nav-dots">
                    <div class="nav-dot"></div>
                    <div class="nav-dot"></div>
                    <div class="nav-dot active"></div>
                </div>
            </div>    
            
            <div style="margin-top: 1rem;">
                <div class="info-card">
                    <p>Please sign and return this letter by {{date('d M Y',strtotime($staff->join_date))}}</p>
                    <h4>Signature :</h4>
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <div class="info-card">
                    <h4>{{ $hr->name }}</h4>
                    <p>Human Resources</p>
                    <p>Cosysta (Xianinfotech LLP)</p>
                </div>
            </div>
            
            <div style="margin-top: 1rem; width: 50%; margin-left: auto;">
                <div class="page-footer">
                    <p>© 2029 Cosysta Technologies. All rights reserved.</p>
                    <p>www.cosysta.com</p>
                </div>
            </div>
        </div>
    </body>
</html>