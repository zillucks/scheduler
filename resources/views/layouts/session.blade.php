@if (session('success'))
<div class="alert alert-success alert-dismissible show fade" role="alert">{{ session('success') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible show fade" role="alert">{{ session('error') }}</div>
@endif
@if (session('deleted'))
<div class="alert alert-danger alert-dismissible show fade" role="alert">{{ session('deleted') }}</div>
@endif