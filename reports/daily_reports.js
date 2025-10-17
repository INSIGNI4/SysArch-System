document.getElementById('printBtn').addEventListener('click', function() {
    let reports = [
        { title: "Today's Newly Added Products", url: 'reports/export_newproducts_pdf.php?type=daily#toolbar=0&navpanes=0' },
        { title: "Today's Sales Report", url: 'reports/export_sales_pdf.php?type=daily#toolbar=0&navpanes=0' },
        { title: "Today's Returns Report", url: 'reports/export_returns_pdf.php?type=daily#toolbar=0&navpanes=0' },
        { title: "Today's Pending Restock Report", url: 'reports/export_restock_pdf.php?type=daily#toolbar=0&navpanes=0' }
    ];

    document.body.innerHTML = '<h1 style="text-align:center; margin-bottom:30px;">Today\'s Reports</h1><div id="printGrid"></div>';

    const grid = document.getElementById('printGrid');
    grid.style.display = 'grid';
    grid.style.gridTemplateColumns = '1fr 1fr';
    grid.style.gridTemplateRows = 'auto auto';
    grid.style.gap = '20px';

    reports.forEach(report => {
        let card = document.createElement('div');
        card.style.display = 'flex';
        card.style.flexDirection = 'column';
        card.style.border = '1px solid #ccc';
        card.style.borderRadius = '10px';
        card.style.overflow = 'hidden';
        card.style.background = '#fff';
        card.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';

        let header = document.createElement('div');
        header.innerText = report.title;
        header.style.textAlign = 'center';
        header.style.padding = '10px';
        header.style.fontWeight = 'bold';
        header.style.backgroundColor = 'fff';
        header.style.color = 'rgba(0,0,0,0.1)';
        card.appendChild(header);

        let iframe = document.createElement('iframe');
        iframe.src = report.url;
        iframe.style.width = '100%';
        iframe.style.height = '450px';
        iframe.style.border = 'none';
        card.appendChild(iframe);

        grid.appendChild(card);
    });

    setTimeout(() => window.print(), 1000);
});