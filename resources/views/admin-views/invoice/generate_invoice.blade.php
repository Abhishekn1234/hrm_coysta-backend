<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $proposal ? $proposal->proposal_title : 'Invoice' }} - Cosysta</title>
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
        <div class="page">
            <div class="header">
                <img src="https://xianinfotech.in/TaskMaster/storage/app/white.png" alt="White Image">
                <div class="nav-dots">
                    <div class="nav-dot active"></div>
                    <div class="nav-dot"></div>
                </div>
            </div>

            <div style="">
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <div>
                        <h2 style="color: var(--primary);">Invoice : {{ $invoice->invoice_number }}</h2>
                        <?php if($quotation) { ?>
                            <p style="font-size: 0.9em; color: var(--text);">Quotation : {{ $quotation->quotation_number }}</p>
                        <?php } ?>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0;">Date: {{date('d M Y',strtotime($invoice->start_date))}}</p>
                        <p style="margin: 0;">Renewal Date: {{date('d M Y',strtotime($invoice->renewal_date))}}</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="info-card">
                        <h4>Cosysta Technologies</h4>
                        <p>Cosysta 65/1095A,Kassim Building, Sebastian Road, Kaloor-682017 Ernakulam, Kerala</p>
                    </div>
                    <div class="info-card">
                        <h4>Bill To:</h4>
                        <p>{{ $client->name }}<br>
                        {!! $client->address !!}</p>
                    </div>
                </div>

                <?php if(count($items) > 0) { ?>
                    <table class="pricing-table">
                        <thead>
                            <tr>
                                <th style="width: 20%">Item</th>
                                <th style="width: 30%">Description</th>
                                <th style="width: 5%">Qty</th>
                                <th style="width: 15%">Price</th>
                                <th style="width: 15%">Tax</th>
                                <th style="width: 15%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            @foreach($items as $k=> $de_p)
                                <?php $total = $total + $de_p->total; ?>
                                <tr>
                                    <td>{{$de_p->item_name}}</td>
                                    <td>{{$de_p->item_description}}</td>
                                    <td>{{$de_p->quantity}}</td>
                                    <td style="text-align:right;">₹ {{$de_p->price}}</td>
                                    <td>{{$de_p->tax}} %</td>
                                    <td style="text-align:right;">₹ {{$de_p->total}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                <?php } ?>
            </div>
         
            <div style="margin-top: 1rem; width: 50%; margin-left: auto;">
                <?php if(count($items) > 0) { ?>
                    <table class="total-table">
                        <tr style="font-weight: bold; background: var(--light);">
                            <td style="text-align:right;">Grand Total:</td>
                            <td style="text-align:right;">₹ {{ $total }}</td>
                        </tr>
                    </table>
                <?php } ?>
            </div>
                
            <div style="display: grid; grid-template-columns: 3fr 1fr; gap: 1rem; margin-bottom: 1rem; margin-top: 1rem;">
                <div class="info-card">
                    <h4>Bank Details :</h4>
                    <p>ACCOUNT NUMBER : 0329073000000914</p>
                    <p>NAME : XIANINFOTECH LLP</p>
                    <p>IFSC Code : SIBL0000329</p>
                    <p>BANK NAME : South Indian Bank , Perinthalmanna</p>
                </div>
                
                <div class="info-card">
                    <img src="https://xianinfotech.in/TaskMaster/storage/app/qr_code.jpeg" alt="White Image" style="width:100%;float:right;">
                </div>
            </div>
        </div>
        
        <div class="page">
            <div class="header">
                <img src="https://xianinfotech.in/TaskMaster/storage/app/white.png" alt="White Image">
                <div class="nav-dots">
                    <div class="nav-dot active"></div>
                    <div class="nav-dot"></div>
                </div>
            </div>

            <div style="margin-top: 1rem;">
                <div class="info-card">
                    <h4>Terms & Conditions:</h4>
                    <p>
                        1. Payment due within 7 days from the date of invoice.<br>
                        2. Late payment penalty of 5% will apply after 7 days.<br>
                        3. Quotation valid for 30 days from the date of issue.<br>
                        4. Any changes in scope will require a revised quotation.<br>
                        5. An interest of 2.5% will be added each month for late payment.
                    </p>
                </div>
            </div>
            
            <?php if($quotation) { ?>
                <?php if($quotation->notes != ''){ ?>
                    <div style="margin-top: 1rem;">
                        <div class="info-card">
                            <h4>Notes : </h4>
                            <p>{{ $quotation->notes }}</p>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
            
            <div style="margin-top: 1rem;">
                <div class="info-card">
                    <h4>Signature :</h4>
                    <h4>Date : {{date('d M Y')}}</h4>
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