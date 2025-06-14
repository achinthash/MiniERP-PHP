
$(document).ready(function () {

    // 1. Stock Levels for All Products
    function fetchStockQuantity() {
        $.post("./../controllers/dashboardController.php", { action: 'StockQuantity' }, function(response) {
            const data = JSON.parse(response);
            const productNames = data.map(p => p.name);
            const stockQuantities = data.map(p => parseInt(p.quantity_in_stock));

            new Chart(document.getElementById('allStockChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: productNames,
                    datasets: [{
                        label: 'Stock Level',
                        data: stockQuantities,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Stock Levels for All Products'
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    }

    // 2. Top Selling Products
    function fetchTopSellingProducts() {
        $.post("./../controllers/dashboardController.php", { action: 'TopSellingProducts' }, function(response) {
            const data = JSON.parse(response);
            const productNames = data.map(p => p.name);
            const totalSold = data.map(p => parseInt(p.total_sold));

            new Chart(document.getElementById('topProductsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: productNames,
                    datasets: [{
                        label: 'Units Sold',
                        data: totalSold,
                        backgroundColor: 'rgba(255, 159, 64, 0.7)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    }

    // 3. Daily Sales (Current Month)
    function fetchDailySales() {
        $.post("./../controllers/dashboardController.php", { action: 'DailySalesThisMonth' }, function(response) {
            const data = JSON.parse(response);
            const days = data.map(d => d.day);
            const totals = data.map(d => parseFloat(d.total_sales));

            new Chart(document.getElementById('dailySalesChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: `Daily Sales (Rs.) - ${new Date().toLocaleString('default', { month: 'long' })}`,
                        data: totals,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rs. ' + value.toLocaleString()
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Day of Month'
                            }
                        }
                    }
                }
            });
        });
    }

    // 4. Payment Method Usage (Pie Chart)
    function fetchPaymentMethods() {
        $.post("./../controllers/dashboardController.php", { action: 'PaymentMethodUsage' }, function(response) {
            const data = JSON.parse(response);
            const labels = data.map(row => row.payment_method);
            const values = data.map(row => parseInt(row.count));

            new Chart(document.getElementById('paymentMethodChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Payment Method Usage',
                        data: values,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    }

    // 5. Monthly Sales (Line Chart)
    function fetchMonthlySales() {
        $.post("./../controllers/dashboardController.php", { action: 'MonthlySales' }, function(response) {
            const data = JSON.parse(response);
            const months = data.map(d => d.month);
            const totals = data.map(d => parseFloat(d.total));

            new Chart(document.getElementById('monthlySalesChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Monthly Sales',
                        data: totals,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.3
                    }]
                }
            });
        });
    }

    // 6. Top Customers
    function fetchTopCustomers() {
        $.post("./../controllers/dashboardController.php", { action: 'TopCustomers' }, function(response) {
            const data = JSON.parse(response);
            const names = data.map(d => d.name);
            const totals = data.map(d => parseFloat(d.total_spent));

            new Chart(document.getElementById('topCustomersChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: names,
                    datasets: [{
                        label: 'Total Spent (Rs.)',
                        data: totals,
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    }

    // Call all functions
    fetchStockQuantity();
    fetchTopSellingProducts();
    fetchDailySales();
    fetchPaymentMethods();
    fetchMonthlySales();
    fetchTopCustomers();

});
