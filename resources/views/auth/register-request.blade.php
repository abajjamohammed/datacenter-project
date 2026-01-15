<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Access - AlphaFold DC</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: 600; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; background: #3498db; color: white; padding: 12px; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; transition: 0.3s; }
        button:hover { background: #2980b9; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #7f8c8d; text-decoration: none; }
        .error { color: #e74c3c; font-size: 0.85rem; margin-top: 5px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Request Access</h2>
    <p style="text-align: center; color: #7f8c8d; margin-bottom: 30px;">Fill this form to apply for a DataCenter account.</p>

    <form action="{{ route('guest.register.submit') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" required value="{{ old('name') }}">
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required value="{{ old('email') }}">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div style="display: flex; gap: 15px;">
            <div class="form-group" style="flex: 1;">
                <label>Profile Type</label>
                <select name="profile" required>
                    <option value="">-- Select --</option>
                    <option value="ingénieur">Ingénieur</option>
                    <option value="enseignant">Enseignant</option>
                    <option value="doctorant">Doctorant</option>
                </select>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Department</label>
                <input type="text" name="department" value="{{ old('department') }}">
            </div>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone') }}">
        </div>

        <div class="form-group">
            <label>Justification (Why do you need access?)</label>
            <textarea name="justification" rows="3" required placeholder="e.g. I need to run simulations for my PhD thesis...">{{ old('justification') }}</textarea>
            @error('justification') <div class="error">{{ $message }}</div> @enderror
        </div>

        <button type="submit">Submit Request</button>
    </form>

    <a href="{{ route('login') }}" class="back-link">&larr; Back to Login</a>
</div>

</body>
</html>