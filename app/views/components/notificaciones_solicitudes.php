<?php
// notificaciones para el usuario

if(isset($_SESSION['usuario'])):

$db = Database::getConnection();
$id_usuario = $_SESSION['usuario']['id_usuario'];

//sacar solicitudes del usuario
$stmt = $db->prepare("SELECT * FROM solicitudes WHERE id_usuario = ? ORDER BY fecha_solicitud DESC LIMIT 5");
$stmt->execute([$id_usuario]);
$solicitudes = $stmt->fetchAll();

//sacar rechazadas
$stmt = $db->prepare("
    SELECT sr.motivo_rechazo, s.nombre_perro, sr.fecha_rechazo
    FROM solicitudes_rechazadas sr
    JOIN solicitudes s ON sr.id_solicitud = s.id_solicitud
    WHERE s.id_usuario = ? 
    ORDER BY sr.fecha_rechazo DESC 
    LIMIT 5
");
$stmt->execute([$id_usuario]);
$rechazadas = $stmt->fetchAll();

$total = count($solicitudes) + count($rechazadas);
?>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
        🔔
        <?php if($total > 0): ?>
            <span class="badge bg-danger"><?= $total ?></span>
        <?php endif; ?>
    </a>
    
    <ul class="dropdown-menu">
        <li class="dropdown-header">Notificaciones</li>
        
        <?php if($total == 0): ?>
            <li class="dropdown-item text-center">No hay notificaciones</li>
        <?php else: ?>
            
            <?php foreach($solicitudes as $s): ?>
                <li class="dropdown-item">
                    <strong>🐶 <?= htmlspecialchars($s['nombre_perro']) ?></strong><br>
                    <?php if($s['estado'] == 'pendiente'): ?>
                        ⏳ Pendiente
                    <?php elseif($s['estado'] == 'aceptada'): ?>
                        ✅ Aceptada
                    <?php endif; ?>
                    <br>
                    <small><?= date('d/m/Y', strtotime($s['fecha_solicitud'])) ?></small>
                </li>
                <li><hr class="dropdown-divider"></li>
            <?php endforeach; ?>
            
            <?php foreach($rechazadas as $r): ?>
                <li class="dropdown-item">
                    <strong>🐶 <?= htmlspecialchars($r['nombre_perro']) ?></strong><br>
                    ❌ Rechazada<br>
                    <small>Motivo: <?= htmlspecialchars($r['motivo_rechazo']) ?></small><br>
                    <small><?= date('d/m/Y', strtotime($r['fecha_rechazo'])) ?></small>
                </li>
                <li><hr class="dropdown-divider"></li>
            <?php endforeach; ?>
            
        <?php endif; ?>
        
        <?php if($total > 0): ?>
            <li class="dropdown-item text-center">
                <a href="/PETPI/public/mis_solicitudes.php">Ver todas</a>
            </li>
        <?php endif; ?>
    </ul>
</li>

<?php endif; ?>
