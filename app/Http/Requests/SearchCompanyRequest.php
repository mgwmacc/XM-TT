<?php

namespace App\Http\Requests;

use App\Services\NasdaqListings\NasdaqListingsService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SearchCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'company_symbol' => 'required',
            'start_date'     => 'required|date_format:Y-m-d|before_or_equal:today',
            'end_date'       => 'required|date_format:Y-m-d|after_or_equal:start_date|before_or_equal:today',
            'email'          => 'required|email',
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.date_format'     => 'The date format is wrong',
            'start_date.before_or_equal' => 'The date must be less or equal than the current date',

            'end_date.date_format'     => 'The date format is wrong',
            'end_date.before_or_equal' => 'The date must be less or equal than the current date',
            'end_date.after_or_equal'  => 'The date must be greater or equal than the Start date',
        ];
    }

    /**
     * @param NasdaqListingsService $nasdaqListingsService
     * @return \Closure[]
     */
    public function after(NasdaqListingsService $nasdaqListingsService)
    {
        return [
            function (Validator $validator) use ($nasdaqListingsService)  {
                if (!$this->isCompanySymbolValid($validator, $nasdaqListingsService)) {
                    $validator->errors()->add(
                        'company_symbol',
                        'The provided Company Symbol is wrong'
                    );
                }
            }
        ];
    }

    /**
     * @param Validator $validator
     * @param NasdaqListingsService $nasdaqListingsService
     * @return bool
     */
    protected function isCompanySymbolValid(
        Validator $validator,
        NasdaqListingsService $nasdaqListingsService
    ) : bool {
        $data = $validator->getData();
        $companySymbol = $data['company_symbol'];

        $availableSymbols = $nasdaqListingsService->getAvailableCompanySymbols();

        return in_array($companySymbol, $availableSymbols);
    }
}
