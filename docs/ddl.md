```sql
-- Companies
create table companies
(
    id         uuid primary key,
    name       varchar not null,
    slug       varchar not null unique,
    created_at timestamp,
    updated_at timestamp
);

-- Users
create table users
(
    id         uuid primary key,
    name       varchar                                                                not null,
    email      varchar                                                                not null,
    password   varchar                                                                not null,
    created_at timestamp,
    updated_at timestamp
);

create table teams
(
    id         uuid primary key,
    company_id uuid    not null references companies (id) on delete cascade,
    name       varchar not null,
    team_lead_id uuid    not null references users (id),
    created_at timestamp,
    updated_at timestamp
);

-- Team Members (pivot for Users and Teams)
create table team_members
(
    id         uuid primary key,
    team_id    uuid           not null references teams (id) on delete cascade,
    user_id    uuid           not null references users (id) on delete cascade,
    created_at timestamp,
    updated_at timestamp,
)

-- Expense Categories
create table expense_categories
(
    id         uuid primary key,
    company_id uuid    not null references companies (id) on delete cascade,
    name       varchar not null,
    created_at timestamp,
    updated_at timestamp
);

-- Expense Reports
create table expense_reports
(
    id           uuid primary key,
    company_id   uuid                                                                                   not null references companies (id) on delete cascade,
    user_id      uuid                                                                                   not null references users (id) on delete cascade,
    title        varchar                                                                                not null,
    total_amount decimal(12, 2)                                                                         not null default 0.00,
    status       varchar check (status in ('draft', 'submitted', 'approved', 'rejected', 'reimbursed')) not null default 'draft',
    submitted_at timestamp,
    created_at   timestamp,
    updated_at   timestamp
);

-- Expenses
create table expenses_report_items
(
    id                uuid primary key,
    expense_report_id uuid           not null references expense_reports (id) on delete cascade,
    category_id       uuid           not null references expense_categories (id),
    description       text           not null,
    amount            decimal(12, 2) not null,
    spent_at          date           not null,
    receipt_path      varchar,
    created_at        timestamp,
    updated_at        timestamp
);

-- Approvals
create table approvals
(
    id                uuid primary key,
    expense_report_id uuid                                                          not null references expense_reports (id) on delete cascade,
    approver_id       uuid                                                          not null references users (id),
    status            varchar check (status in ('pending', 'approved', 'rejected')) not null default 'pending',
    decision_at       timestamp,
    comment           text,
    created_at        timestamp,
    updated_at        timestamp
);

-- Reimbursements
create table reimbursements
(
    id                uuid primary key,
    expense_report_id uuid           not null references expense_reports (id) on delete cascade,
    processed_by      uuid           not null references users (id),
    amount            decimal(12, 2) not null,
    paid_at           timestamp,
    payment_method    varchar,
    created_at        timestamp,
    updated_at        timestamp
);

-- Invoices
create table invoices
(
    id             uuid primary key,
    company_id     uuid                                                             not null references companies (id) on delete cascade,
    issued_by_id   uuid                                                             not null references users (id),
    invoice_number varchar                                                          not null,
    status         varchar check (status in ('draft', 'issued', 'paid', 'overdue')) not null default 'draft',
    total_amount   decimal(12, 2)                                                   not null default 0.00,
    issued_at      timestamp,
    due_date       timestamp,
    paid_at        timestamp,
    notes          text,
    created_at     timestamp,
    updated_at     timestamp
);

-- Invoice Items (pivot for Expenses)
create table invoice_items
(
    id          uuid primary key,
    invoice_id  uuid           not null references invoices (id) on delete cascade,
    expense_id  uuid           not null references expenses (id),
    description text,
    amount      decimal(12, 2) not null,
    created_at  timestamp,
    updated_at  timestamp,
    unique (invoice_id, expense_id)
);
```
