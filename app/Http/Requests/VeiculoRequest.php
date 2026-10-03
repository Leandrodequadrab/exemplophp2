<?php

namespace App\Http\Requests;

use App\Models\Veiculo;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VeiculoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize the input before validation.
     */
    protected function prepareForValidation(): void
    {
        $placa = $this->input('placa');

        $this->merge([
            'placa' => $placa ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $placa)) : null,
            'quilometragem' => $this->input('quilometragem') ?: 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $anoMaximo = (int) date('Y') + 1;

        return [
            'marca' => ['required', 'string', 'max:60'],
            'modelo' => ['required', 'string', 'max:80'],
            'versao' => ['nullable', 'string', 'max:120'],
            'categoria' => ['required', Rule::in(Veiculo::CATEGORIAS)],
            'ano_fabricacao' => ['required', 'integer', 'min:1950', "max:{$anoMaximo}"],
            'ano_modelo' => ['required', 'integer', 'gte:ano_fabricacao', "max:{$anoMaximo}"],
            'cor' => ['required', 'string', 'max:40'],
            'combustivel' => ['required', Rule::in(Veiculo::COMBUSTIVEIS)],
            'cambio' => ['required', Rule::in(Veiculo::CAMBIOS)],
            'quilometragem' => ['required', 'integer', 'min:0', 'max:9999999'],
            'placa' => [
                'nullable',
                'regex:/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/',
                Rule::unique('veiculos', 'placa')->ignore($this->route('veiculo')),
            ],
            'preco' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'status' => ['required', Rule::in(array_keys(Veiculo::STATUS))],
            'foto_url' => ['nullable', 'url', 'max:500'],
            'descricao' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser um texto.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'numeric' => 'O campo :attribute deve ser um número.',
            'url' => 'O campo :attribute deve ser uma URL válida (https://...).',
            'in' => 'Selecione um valor válido para :attribute.',
            'max' => 'O campo :attribute ultrapassa o limite permitido (:max).',
            'min' => 'O campo :attribute deve ser no mínimo :min.',
            'unique' => 'Já existe um veículo cadastrado com esta :attribute.',
            'ano_modelo.gte' => 'O ano do modelo não pode ser menor que o ano de fabricação.',
            'placa.regex' => 'Informe uma placa válida (ex.: ABC1234 ou ABC1D23).',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'marca' => 'marca',
            'modelo' => 'modelo',
            'versao' => 'versão',
            'categoria' => 'categoria',
            'ano_fabricacao' => 'ano de fabricação',
            'ano_modelo' => 'ano do modelo',
            'cor' => 'cor',
            'combustivel' => 'combustível',
            'cambio' => 'câmbio',
            'quilometragem' => 'quilometragem',
            'placa' => 'placa',
            'preco' => 'preço',
            'status' => 'status',
            'foto_url' => 'URL da foto',
            'descricao' => 'descrição',
        ];
    }
}
