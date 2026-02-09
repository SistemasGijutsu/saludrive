<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Paciente - SaludGo</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Paciente'); ?></h1>
            <a href="../../routes/router.php?action=logout" class="btn-logout">Cerrar sesión</a>
        </header>
        
        <div class="dashboard-content">
            <h2>Dashboard del Paciente</h2>
            <p>Esta es tu área personal. Aquí podrás gestionar tus citas y consultas médicas.</p>
            
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Mis Citas</h3>
                    <p>Gestiona tus citas médicas</p>
                </div>
                
                <div class="card">
                    <h3>Mis Médicos</h3>
                    <p>Lista de profesionales</p>
                </div>
                
                <div class="card">
                    <h3>Historial</h3>
                    <p>Consulta tu historial médico</p>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-logout {
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .btn-logout:hover {
            background: #c0392b;
        }
        
        .dashboard-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .card {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .card h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
    </style>
</body>
</html>
