<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Pages;

use App\Enums\ApprovalStatus;
use App\Filament\Admin\Resources\Reports\ReportResource;
use App\Models\Approval;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

final class ApproveReport extends Page implements HasSchemas
{
    use InteractsWithRecord;
    use InteractsWithSchemas;

    public ?array $data = [];

    protected static string $resource = ReportResource::class;

    protected static ?string $title = 'Approve Report';

    protected string $view = 'filament.admin.resources.reports.pages.approve-report';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->form->fill();
        $this->setTitle();
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Approval::query()->create([
            'company_id' => auth()->user()->company_id,
            'report_id' => $this->record->getKey(),
            'approver_id' => auth()->user()->id,
            'status' => $data['status'],
            'level' => 'manager',
            'comments' => $data['comments'],
            'approved_at' => now(),
        ]);

        // notify user $this->record->user
    }

    public function setTitle(): void
    {
        self::$title = sprintf('Report Number %s made by %s', $this->record->id, $this->record->user->name);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('status')
                ->options(ApprovalStatus::class)
                ->enum(ApprovalStatus::class)
                ->required(),
            TextInput::make('comments')
                ->required()
                ->minlength(3)
                ->maxLength(255),
        ])->statePath('data');
    }
}
