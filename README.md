# Aegis Expense Management System - Project Documentation

## Table of Contents
- [Project Overview](#project-overview)
- [Architecture Overview](#architecture-overview)
- [Database Schema Design](#database-schema-design)
- [User Roles & Permissions](#user-roles--permissions)
- [Core Modules Documentation](#core-modules-documentation)
- [Development Guidelines](#development-guidelines)
- [Deployment & DevOps](#deployment--devops)

---

## Project Overview

### Purpose
Aegis is a comprehensive expense management system designed to streamline the process of submitting, approving, and reimbursing business expenses. It provides a centralized platform for organizations to manage their expense workflows efficiently while maintaining compliance with company policies and financial regulations.

### Core Features
- **Multi-tenancy**: Isolated environments for different companies
- **Role-based access control**: Different permissions for admins, managers, and employees
- **Expense reporting**: Digital submission and tracking of expenses
- **Approval workflows**: Configurable multi-level approval processes
- **Reimbursement processing**: Automated payment tracking and reconciliation
- **Analytics dashboards**: Expense trends, budget tracking, and financial insights
- **Integrations**: Connections with accounting software, payment processors, and HR systems

### Target Users
- **Companies**: Organizations of all sizes looking to digitize and streamline expense management
- **Finance Teams**: CFOs, controllers, and accountants who need to monitor and control company spending
- **Managers**: Department heads who need to approve team expenses
- **Employees**: Staff members who need to submit expense reports

### Business Goals
- Reduce the time spent on expense management by 70%
- Decrease expense processing costs by 50%
- Improve policy compliance and reduce fraudulent expense submissions
- Provide actionable insights into spending patterns to optimize budgets
- Enable seamless integration with existing financial systems

---

## Architecture Overview

### High-Level System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Client Layer                             │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐    │
│  │  Admin Panel  │    │ Company Panel │    │ Employee Panel│    │
│  └───────────────┘    └───────────────┘    └───────────────┘    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      Application Layer                          │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐    │
│  │    Laravel    │    │  FilamentPHP  │    │   Livewire    │    │
│  │   Framework   │    │  Admin Panels │    │  Components   │    │
│  └───────────────┘    └───────────────┘    └───────────────┘    │
│                                                                 │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐    │
│  │    Models     │    │  Controllers  │    │    Services   │    │
│  └───────────────┘    └───────────────┘    └───────────────┘    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                        Data Layer                               │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐    │
│  │  PostgreSQL   │    │     Redis     │    │  File Storage │    │
│  │   Database    │    │     Cache     │    │  (S3/Local)   │    │
│  └───────────────┘    └───────────────┘    └───────────────┘    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                     Integration Layer                           │
│  ┌───────────────┐    ┌───────────────┐    ┌───────────────┐    │
│  │  Accounting   │    │    Payment    │    │      API      │    │
│  │   Software    │    │   Processors  │    │    Gateway    │    │
│  └───────────────┘    └───────────────┘    └───────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

### Tech Stack

#### Backend
- **Laravel 12**: PHP framework for the application core
- **FilamentPHP 4**: Admin panel framework
- **PostgreSQL**: Primary database
- **Redis**: Caching and queue management

#### Frontend
- **TailwindCSS**: Utility-first CSS framework
- **Livewire**: Server-driven frontend components
- **Alpine.js**: Lightweight JavaScript framework for interactivity

#### DevOps & Infrastructure
- **Docker**: Containerization
- **GitHub Actions**: CI/CD pipeline
- **AWS/Digital Ocean**: Cloud hosting

### Multi-Panel Structure Breakdown

#### Admin Panel (Super Admin)
- **Purpose**: System-wide administration and configuration
- **Users**: Platform administrators
- **Features**:
    - Company management
    - System configuration
    - Global reporting
    - User management across all companies

#### Company Panel (Company Admin/Manager)
- **Purpose**: Company-specific administration
- **Users**: Company administrators and managers
- **Features**:
    - Company settings management
    - Department configuration
    - Approval workflow setup
    - Company-wide reporting
    - User management within the company

#### Employee Panel
- **Purpose**: Day-to-day expense management
- **Users**: Regular employees
- **Features**:
    - Expense submission
    - Report creation
    - Reimbursement tracking
    - Personal dashboard

---

## Database Schema Design

### Entity Relationship Diagram (ERD)

```
┌────────────────┐       ┌────────────────┐       ┌────────────────┐
│    Companies   │       │      Users     │       │  Departments   │
├────────────────┤       ├────────────────┤       ├────────────────┤
│ id             │       │ id             │       │ id             │
│ name           │◄──────┤ company_id     │       │ company_id     │
│ subdomain      │       │ department_id  │◄──────┤ name           │
│ settings       │       │ name           │       │ manager_id     │
│ created_at     │       │ email          │       │ budget         │
│ updated_at     │       │ password       │       │ created_at     │
└────────────────┘       │ role           │       │ updated_at     │
                         │ created_at     │       └────────────────┘
                         │ updated_at     │
                         └────────────────┘
                                 ▲
                                 │
                                 │
┌────────────────┐       ┌────────────────┐       ┌────────────────┐
│    Expenses    │       │     Reports    │       │   Categories   │
├────────────────┤       ├────────────────┤       ├────────────────┤
│ id             │       │ id             │       │ id             │
│ company_id     │       │ company_id     │       │ company_id     │
│ user_id        │       │ user_id        │◄──────┤ name           │
│ report_id      │◄──────┤ title          │       │ description    │
│ category_id    │       │ description    │       │ created_at     │
│ amount         │       │ status         │       │ updated_at     │
│ date           │       │ submitted_at   │       └────────────────┘
│ description    │       │ created_at     │
│ receipt_path   │       │ updated_at     │
│ status         │       └────────────────┘
│ created_at     │               ▲
│ updated_at     │               │
└────────────────┘               │
                                 │
┌────────────────┐       ┌────────────────┐       ┌────────────────┐
│    Approvals   │       │ Reimbursements │       │ Notifications  │
├────────────────┤       ├────────────────┤       ├────────────────┤
│ id             │       │ id             │       │ id             │
│ company_id     │       │ company_id     │       │ company_id     │
│ report_id      │◄──────┤ report_id      │◄──────┤ user_id        │
│ approver_id    │       │ amount         │       │ type           │
│ level          │       │ status         │       │ title          │
│ status         │       │ payment_method │       │ content        │
│ comments       │       │ payment_date   │       │ read_at        │
│ approved_at    │       │ reference      │       │ created_at     │
│ created_at     │       │ created_at     │       │ updated_at     │
│ updated_at     │       │ updated_at     │       └────────────────┘
└────────────────┘       └────────────────┘
```

### Key Tables

#### Companies
- Stores organization information
- Contains company-specific settings and configuration
- Primary tenant identifier for multi-tenancy

#### Users
- All system users with role designation
- Linked to specific company (tenant)
- May belong to a department

#### Departments
- Organizational units within companies
- Has budget allocation and manager assignment
- Used for expense categorization and approval routing

#### Categories
- Expense categories (e.g., Travel, Meals, Office Supplies)
- Can be system-defined or company-specific
- Used for expense classification and reporting

#### Expenses
- Individual expense entries
- Contains amount, date, description, and receipt information
- Linked to a report, user, company, and category

#### Reports
- Collection of expenses submitted together
- Has workflow status (Draft, Submitted, In Review, Approved, Rejected)
- Linked to approvals and reimbursements

#### Approvals
- Tracks approval workflow for expense reports
- Contains approval level, status, and comments
- Multiple approvals may exist for a single report (multi-level workflow)

#### Reimbursements
- Tracks payment status for approved reports
- Contains payment method, date, and reference information
- Final step in the expense lifecycle

#### Notifications
- System messages for users
- Triggered by workflow events (submission, approval, rejection, etc.)
- Supports multiple notification channels

### Multi-Tenancy Strategy

#### Data Isolation Approach
- **Company ID Scoping**: All database tables include a `company_id` column
- **Global Scope**: Laravel global scopes automatically filter queries by the current company
- **Domain/Subdomain Strategy**: Each company accesses the system via a unique subdomain (company.aegis.com)

#### Implementation Details
- **Middleware**: `CompanyTenantMiddleware` identifies the current company from the subdomain
- **Service Container**: Current company is bound to the service container for global access
- **Query Scoping**: Automatic filtering of all queries to ensure data isolation
- **Shared Resources**: System-level data (like system categories) accessible across tenants

---

## User Roles & Permissions

### Role Definitions

#### Super Admin
- **Description**: System-wide administrator with full access to all features and companies
- **Responsibilities**:
    - Platform configuration
    - Company account management
    - System monitoring and maintenance
    - Global reporting and analytics

#### Company Admin
- **Description**: Administrator for a specific company
- **Responsibilities**:
    - Company settings management
    - User management within the company
    - Approval workflow configuration
    - Company-wide reporting

#### Manager
- **Description**: Department head or team leader
- **Responsibilities**:
    - Expense approval
    - Department budget management
    - Team expense monitoring
    - Department reporting

#### Employee
- **Description**: Regular staff member
- **Responsibilities**:
    - Expense submission
    - Report creation
    - Personal expense tracking

### Access Control List (ACL) Matrix

| Resource/Action              | Super Admin | Company Admin | Manager | Employee |
|------------------------------|-------------|---------------|---------|----------|
| **Admin Panel**              |             |               |         |          |
| System Configuration         | ✓           | ✗             | ✗       | ✗        |
| Company Management           | ✓           | ✗             | ✗       | ✗        |
| Global Reporting             | ✓           | ✗             | ✗       | ✗        |
| **Company Panel**            |             |               |         |          |
| Company Settings             | ✓           | ✓             | ✗       | ✗        |
| User Management              | ✓           | ✓             | ✗       | ✗        |
| Department Management        | ✓           | ✓             | ✗       | ✗        |
| Category Management          | ✓           | ✓             | ✗       | ✗        |
| Approval Workflow Config     | ✓           | ✓             | ✗       | ✗        |
| Company Reporting            | ✓           | ✓             | ✓       | ✗        |
| **Department Management**    |             |               |         |          |
| Department Settings          | ✓           | ✓             | ✓*      | ✗        |
| Department Budget            | ✓           | ✓             | ✓*      | ✗        |
| Department Reporting         | ✓           | ✓             | ✓*      | ✗        |
| **Expense Management**       |             |               |         |          |
| Create Expenses              | ✓           | ✓             | ✓       | ✓        |
| Edit Own Expenses            | ✓           | ✓             | ✓       | ✓        |
| Edit Any Expense             | ✓           | ✓             | ✗       | ✗        |
| Delete Own Expenses          | ✓           | ✓             | ✓       | ✓        |
| Delete Any Expense           | ✓           | ✓             | ✗       | ✗        |
| **Report Management**        |             |               |         |          |
| Create Reports               | ✓           | ✓             | ✓       | ✓        |
| Submit Reports               | ✓           | ✓             | ✓       | ✓        |
| View Own Reports             | ✓           | ✓             | ✓       | ✓        |
| View Department Reports      | ✓           | ✓             | ✓       | ✗        |
| View All Company Reports     | ✓           | ✓             | ✗       | ✗        |
| **Approval Workflow**        |             |               |         |          |
| Approve/Reject Reports       | ✓           | ✓             | ✓*      | ✗        |
| Override Approvals           | ✓           | ✓             | ✗       | ✗        |
| **Reimbursement**            |             |               |         |          |
| Process Reimbursements       | ✓           | ✓             | ✗       | ✗        |
| View Own Reimbursements      | ✓           | ✓             | ✓       | ✓        |
| View All Reimbursements      | ✓           | ✓             | ✗       | ✗        |
| **Analytics & Reporting**    |             |               |         |          |
| Personal Dashboard           | ✓           | ✓             | ✓       | ✓        |
| Department Dashboard         | ✓           | ✓             | ✓       | ✗        |
| Company Dashboard            | ✓           | ✓             | ✗       | ✗        |
| System Dashboard             | ✓           | ✗             | ✗       | ✗        |
| Export Reports               | ✓           | ✓             | ✓       | ✓*       |

*✓* = Access granted  
*✗* = Access denied  
*✓** = Limited access (own department only or with restrictions)

### Implementation Strategy

- **Spatie Permission Package**: Utilizing Laravel's Spatie Permission package for role and permission management
- **Policy-Based Authorization**: Laravel policies for fine-grained access control
- **Filament Shield**: Integration with Filament for admin panel access control
- **Middleware**: Role-based middleware for route protection
- **UI Adaptation**: Dynamic UI elements based on user permissions

---

## Core Modules Documentation

### Expense Reporting Flow

#### Overview
The expense reporting module allows users to create, submit, and track expense reports through a defined workflow.

#### Process Flow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Create    │     │   Submit    │     │   Review    │     │  Approval   │
│   Expense   │────►│   Report    │────►│  Process    │────►│  Decision   │
└─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
                                                                   │
                                                                   ▼
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│ Reimbursed  │◄────│  Payment    │◄────│ Accounting  │◄────│  Approved   │
│  Complete   │     │  Process    │     │ Integration │     │             │
└─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
```

#### Key Components

1. **Expense Creation**
    - Digital receipt capture (upload, email, or mobile scan)
    - OCR for automatic data extraction
    - Category and project assignment
    - Tax and currency handling

2. **Report Submission**
    - Grouping expenses into reports
    - Policy compliance checking
    - Submission to appropriate approval chain

3. **Status Tracking**
    - Real-time status updates
    - Timeline view of report progress
    - Notification system for status changes

### Approval Workflow

#### Overview
The approval workflow module manages the review and approval process for expense reports, supporting multi-level approvals based on company policies.

#### Workflow Configuration

Companies can configure approval workflows based on:
- Expense amount thresholds
- Expense categories
- Department hierarchies
- Special approval requirements

#### Multi-Level Approval Process

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Report    │     │   Level 1   │     │   Level 2   │     │   Level 3   │
│  Submitted  │────►│   Manager   │────►│ Department  │────►│   Finance   │
└─────────────┘     └─────────────┘     │    Head     │     │    Team     │
                                        └─────────────┘     └─────────────┘
```

#### Key Features

1. **Dynamic Routing**
    - Automatic determination of approval chain
    - Skip-level logic for special cases
    - Delegation capabilities for approvers

2. **Approval Actions**
    - Approve/Reject/Request clarification
    - Partial approvals
    - Bulk approval capabilities
    - Mobile approval support

3. **Compliance Enforcement**
    - Policy violation flagging
    - Audit trail of approval decisions
    - Comment system for communication

### Reimbursement Processing

#### Overview
The reimbursement module handles the payment process for approved expense reports, tracking from approval to payment completion.

#### Reimbursement Flow

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Report    │     │ Reimbursement│     │  Payment    │     │  Payment    │
│  Approved   │────►│   Created    │────►│ Processing  │────►│  Complete   │
└─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
                                                                   │
                                                                   ▼
                                                            ┌─────────────┐
                                                            │  Employee   │
                                                            │ Notification │
                                                            └─────────────┘
```

#### Key Features

1. **Payment Methods**
    - Direct deposit integration
    - PayPal/Venmo support
    - Check processing
    - Crypto payment options

2. **Batch Processing**
    - Grouping reimbursements for efficient processing
    - Scheduled payment runs
    - Bulk export for accounting systems

3. **Reconciliation**
    - Payment tracking and confirmation
    - Accounting system synchronization
    - Tax reporting support

### Analytics & Reports

#### Overview
The analytics module provides insights into expense data through dashboards, reports, and data visualization tools.

#### Dashboard Types

1. **Employee Dashboard**
    - Personal expense summary
    - Pending reports and reimbursements
    - Historical expense trends

2. **Manager Dashboard**
    - Team expense overview
    - Budget tracking vs. actual
    - Approval queue management

3. **Finance Dashboard**
    - Company-wide expense analytics
    - Department comparisons
    - Category breakdown analysis
    - Policy compliance metrics

#### Key Features

1. **Interactive Visualizations**
    - Expense trends over time
    - Category distribution charts
    - Department comparison graphs
    - Geographical expense mapping

2. **Export Capabilities**
    - PDF report generation
    - Excel/CSV data export
    - Scheduled report delivery
    - Custom report builder

3. **Budget Management**
    - Budget vs. actual tracking
    - Forecast projections
    - Anomaly detection
    - Spending alerts
