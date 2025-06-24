<?php

namespace App\Exports;

use App\Exports\Traits\ExcelColumnAutoSize;
use App\Models\Contact;
use Generator;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Illuminate\Http\Request;

class ContactExport implements FromGenerator, WithEvents, WithMapping, WithHeadings, WithDefaultStyles, WithTitle
{
    use ExcelColumnAutoSize;

    private ?int $onlyLibraryId = NULL;
    private $onlyActive = NULL;
    private $onlyExited = NULL;
    private $topic = NULL;

    public function filter(Request $request)
    {
        $onlyActive = $request->input('active');
        if ($onlyActive !== NULL)
            $this->onlyActive = $onlyActive == "1";

        $onlyExited = $request->input('exited');
        if ($onlyExited !== NULL)
            $this->onlyExited = $onlyExited == "1";

        $this->topic = $request->input('topic');

        return $this;
    }

    public function forLibrary($libraryId): self {
        $this->onlyLibraryId = $libraryId;

        return $this;
    }

    public function generator(): Generator
    {
        $qry = Contact::query();
        $qry->join('person_functions', 'person_functions.id', '=', 'contacts.person_function_id');
        $qry->join('libraries', 'libraries.id', '=', 'person_functions.library_id');

        if($this->onlyLibraryId !== NULL)
            $qry->where('libraries.id', $this->onlyLibraryId);

        if ($this->onlyActive !== NULL)
            $qry->where('libraries.is_active', $this->onlyActive);

        if ($this->onlyExited !== NULL)
            $qry->where('person_functions.exited', $this->onlyExited);

        if ($this->topic !== NULL)
            $qry->where('topic', $this->topic);

        $mapped = $qry->get()->sortBy(function ($s) {
            return $s->personFunction->person->last_name;
        });

        foreach ($mapped as $m)
            yield $m;
    }

    public function headings(): array
    {
        return [
            trans('person.lastName'),
            trans('person.firstName'),
            trans('person.gender'),
            trans('library.bibcode'),
            trans('library.name'),
            trans('library.isActive'),
            trans('library.shippingPoBox'),
            trans('library.shippingStreet'),
            trans('library.shippingZip'),
            trans('library.shippingLocation'),
            trans('library.differentBillingAddress'),
            trans('library.billingName'),
            trans('library.billingNameAddition'),
            trans('library.billingPoBox'),
            trans('library.billingStreet'),
            trans('library.billingZip'),
            trans('library.billingLocation'),
            trans('library.associatedType'),
            trans('library.uniRegulations'),
            trans('library.faculty'),
            trans('library.departement'),
            trans('personFunction.exited'),
            trans('personFunction.phone'),
            trans('personFunction.email'),
            trans('personFunction.work'),
            trans('contact.singular'),
            trans('contact.comment'),
        ];
    }

    public function map($contact): array
    {
        return [
            $contact->personFunction->person->last_name,
            $contact->personFunction->person->first_name,
            $contact->personFunction->person->gender,
            $contact->personFunction->library->bibcode,
            $contact->personFunction->library->name,
            $contact->personFunction->library->is_active ? trans('general.yes') : trans('general.no'),
            $contact->personFunction->library->shipping_pobox,
            $contact->personFunction->library->shipping_street,
            $contact->personFunction->library->shipping_zip,
            $contact->personFunction->library->shipping_location,
            $contact->personFunction->library->different_billing_address,
            $contact->personFunction->library->billing_name,
            $contact->personFunction->library->billing_name_addition,
            $contact->personFunction->library->billing_pobox,
            $contact->personFunction->library->billing_street,
            $contact->personFunction->library->billing_zip,
            $contact->personFunction->library->billing_location,
            $contact->personFunction->library->associated_type?->translate(),
            $contact->personFunction->library->uni_regulations,
            $contact->personFunction->library->faculty?->translate(),
            $contact->personFunction->library->departement,
            $contact->personFunction->exited ? trans('general.yes') : trans('general.no'),
            $contact->personFunction->phone,
            $contact->personFunction->email,
            $contact->personFunction->work?->translate(),
            $contact->topic?->translate(),
            $contact->comment

        ];
    }

    public function title(): string
    {
        return trans('export.contactSheet');
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return $defaultStyle->getAlignment()->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
        );
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->autoSizeColumns($event);
            },
        ];
    }
}