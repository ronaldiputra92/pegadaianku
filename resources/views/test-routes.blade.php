<!DOCTYPE html>
<html>
<head>
    <title>Test Routes</title>
</head>
<body>
    <h1>Route Testing</h1>
    
    <h2>Customer Documents Routes:</h2>
    <ul>
        <li><a href="{{ url('/customer-documents') }}">Customer Documents Index</a></li>
        <li><a href="{{ url('/customer-documents/create') }}">Create Document</a></li>
    </ul>
    
    <h2>Customer History Routes:</h2>
    <ul>
        <li><a href="{{ url('/customer-history') }}">Customer History Index</a></li>
    </ul>
    
    <h2>Other Routes:</h2>
    <ul>
        <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        <li><a href="{{ url('/customers') }}">Customers</a></li>
    </ul>
</body>
</html>