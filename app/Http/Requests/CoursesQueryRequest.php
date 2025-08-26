<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CoursesQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return ['term' => ['nullable', 'string', 'max:20']]; // example
    }
}
