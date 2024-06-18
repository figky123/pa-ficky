SELECT 
    user_app.id_user,
    user_app.nama,
    user_app.email,
    laporan.id_laporan,
    laporan.tgl_laporan,
    laporan.status_laporan
FROM 
    user_app
LEFT JOIN 
    laporan ON user_app.id_user = laporan.id_user;