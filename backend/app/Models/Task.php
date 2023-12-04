<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    
    function handleDeleteTask(event) {

        const deleteButton = event.currentTarget

        const taskElement = deleteButton.closest('li')

        const taskId = taskElement.dataset.id
        console.log(taskId)

        const url = `${API.endpoint}/${taskId}`
        fetch (url, {
            method: 'DELETE'
        } );

        taskElement.remove()
    }
}
