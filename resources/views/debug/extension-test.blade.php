<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extension Debug Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Extension Debug Test</h1>
        
        <!-- Test Data -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Test Data</h2>
            <button id="testData" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Check Test Data
            </button>
            <div id="testDataResult" class="mt-4 p-4 bg-gray-50 rounded hidden"></div>
        </div>

        <!-- Create Sample -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Create Sample Transaction</h2>
            <button id="createSample" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Create Sample Transaction
            </button>
            <div id="createSampleResult" class="mt-4 p-4 bg-gray-50 rounded hidden"></div>
        </div>

        <!-- Test Search -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Test Transaction Search</h2>
            <div class="flex mb-4">
                <input type="text" id="searchCode" placeholder="Enter transaction code..." 
                       class="flex-1 px-3 py-2 border rounded-l">
                <button id="searchTransaction" class="bg-purple-500 text-white px-4 py-2 rounded-r hover:bg-purple-600">
                    Search
                </button>
            </div>
            <div id="searchResult" class="mt-4 p-4 bg-gray-50 rounded hidden"></div>
        </div>
    </div>

    <script>
        // Test data
        document.getElementById('testData').addEventListener('click', function() {
            fetch('/test-extension-data')
                .then(response => response.json())
                .then(data => {
                    const result = document.getElementById('testDataResult');
                    result.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                    result.classList.remove('hidden');
                })
                .catch(error => {
                    const result = document.getElementById('testDataResult');
                    result.innerHTML = '<div class="text-red-500">Error: ' + error.message + '</div>';
                    result.classList.remove('hidden');
                });
        });

        // Create sample
        document.getElementById('createSample').addEventListener('click', function() {
            fetch('/create-sample-transaction')
                .then(response => response.json())
                .then(data => {
                    const result = document.getElementById('createSampleResult');
                    result.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                    result.classList.remove('hidden');
                })
                .catch(error => {
                    const result = document.getElementById('createSampleResult');
                    result.innerHTML = '<div class="text-red-500">Error: ' + error.message + '</div>';
                    result.classList.remove('hidden');
                });
        });

        // Test search
        document.getElementById('searchTransaction').addEventListener('click', function() {
            const code = document.getElementById('searchCode').value;
            if (!code) {
                alert('Please enter transaction code');
                return;
            }

            fetch('/extensions/transaction-details?' + new URLSearchParams({
                transaction_code: code
            }))
                .then(response => response.json())
                .then(data => {
                    const result = document.getElementById('searchResult');
                    result.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                    result.classList.remove('hidden');
                })
                .catch(error => {
                    const result = document.getElementById('searchResult');
                    result.innerHTML = '<div class="text-red-500">Error: ' + error.message + '</div>';
                    result.classList.remove('hidden');
                });
        });
    </script>
</body>
</html>