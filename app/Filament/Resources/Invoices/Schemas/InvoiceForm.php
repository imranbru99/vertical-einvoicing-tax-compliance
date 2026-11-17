<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('number')->required()->unique(ignoreRecord: true),
                Select::make('customer_id')->relationship('customer', 'name')->required()->searchable(),
                Select::make('tax_jurisdiction_id')->relationship('jurisdiction', 'name')->required()->searchable(),
                Select::make('tax_rule_id')->relationship('taxRule', 'name')->searchable(),
                Select::make('status')->options(\App\Enums\InvoiceStatus::options())->required(),
                TextInput::make('currency')->maxLength(3)->default('USD'),
                DatePicker::make('issue_date')->required(),
                DatePicker::make('due_date'),
                TextInput::make('subtotal')->numeric(),
                TextInput::make('tax_amount')->numeric(),
                TextInput::make('total')->numeric(),
                TextInput::make('tax_rate_percent')->numeric()->suffix('%'),
                TextInput::make('buyer_tax_id')->maxLength(80),
                TextInput::make('seller_tax_id')->maxLength(80),
                TextInput::make('clearance_reference')->maxLength(120),
                Textarea::make('notes')->rows(2)->columnSpanFull(),
        ]);
    }
}
