<?php

namespace App\Models;

use CodeIgniter\Model;

class NoticiaModel extends Model
{
    protected $table            = 'noticias';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'titulo',
        'slug',
        'descripcion',
        'contenido',
        'imagen',
        'fecha_publicacion',
        'autor',
        'publicado',
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $returnType       = 'array';

    /**
     * Busca una noticia publicada por slug
     */
    public function obtenerPorSlug($slug)
    {
        return $this
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Lista noticias ordenadas por fecha, con opción de paginación
     */
    public function listarPublicadas(int $cantidad = 10)
    {
        return $this
            ->where('publicado', true)
            ->orderBy('fecha_publicacion', 'DESC')
            ->paginate($cantidad);
    }

    /**
     * Para obtener el total de noticias publicadas (usado en paginación)
     */
    public function contarPublicadas()
    {
        return $this->where('publicado', true)->countAllResults();
    }
}