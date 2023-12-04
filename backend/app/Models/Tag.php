<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    
    function handleDeleteMovie(event) {

        const deleteButton = event.currentTarget

    const tagElement = deleteButton.closest('li')

    const tagId = tagElement.dataset.id
    console.log(tagId)

    const url = `${API_.endpoint}/${tagId}`
    fetch (url, {
        method: 'DELETE'
    } );
// Supprimer l'element movie dans le DOM
movieElement.remove()

}
}
