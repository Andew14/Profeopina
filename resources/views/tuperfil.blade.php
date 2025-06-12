<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/css/configcuenta.css">
    <link rel="stylesheet" href="/css/perfil_student.css">
    <title>{{ __('messages.profile') }}</title>
    
</head>
<body>
    @extends('layouts.logueado')
    @section('titulo', __('messages.profile'))
    @section('contenido')
        <p><strong>{{ __('messages.name') }}:</strong> {{ $student->name }}</p>
        <p><strong>{{ __('messages.email') }}:</strong> {{ $student->email }}</p>
        <p><strong>{{ __('messages.password') }}:</strong> ********</p>
        <a href="#" class="editar" id="editProfileBtn">
            <i class="fas fa-pencil-alt"></i> <strong>{{ __('messages.edit') }}</strong>
        </a>
        
        <!-- Modal -->
        <div id="editProfileModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>{{ __('messages.edit_profile') }}</h2>
                <form action="{{ route('profile.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">{{ __('messages.name') }}:</label>
                        <input type="text" id="name" name="name" value="{{ $student->name }}">
                    </div>
                    <div>
                        <label for="email">{{ __('messages.email') }}:</label>
                        <input type="email" id="email" name="email" value="{{ $student->email }}">
                    </div>
                    <div>
                        <label for="password">{{ __('messages.password') }}:</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <button type="submit">{{ __('messages.save') }}</button>
                </form>
            </div>
        </div>

        <script>
            document.getElementById('editProfileBtn').onclick = function() {
                document.getElementById('editProfileModal').style.display = "block";
            }
            document.getElementsByClassName('close')[0].onclick = function() {
                document.getElementById('editProfileModal').style.display = "none";
            }
            window.onclick = function(event) {
                if (event.target == document.getElementById('editProfileModal')) {
                    document.getElementById('editProfileModal').style.display = "none";
                }
            }
        </script>
    @endsection
</body>
</html>
