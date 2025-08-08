<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Pages;

use App\Actions\Approval\CreateApprovalAction;
use App\DTOs\ApprovalDTO;
use App\Enums\ApprovalStatus;
use App\Filament\Admin\Resources\Reports\ReportResource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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

    public function save(CreateApprovalAction $action): void
    {
        $data = $this->form->getState();
        $reportId = $this->record->getKey();

        $action->execute(
            ApprovalDTO::make(
                companyId: auth()->user()->company_id,
                reportId: $reportId,
                approverId: auth()->user()->id,
                status: $data['status'],
                comments: $data['comments'],
            )
        );

        Notification::make()
            ->title(sprintf('Your report %s status was %s', $reportId, $data['status']->value))
            ->sendToDatabase($this->record->user);

        redirect()->route('filament.admin.resources.approvals.index');
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
