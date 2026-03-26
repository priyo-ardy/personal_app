<aside class="app-sidebar bg-body-secondary" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= base_url() . 'MainMenu'; ?>" class="brand-link">
            <img
                src="<?= base_url() . 'img/favicon.png'; ?>"
                alt="Schlemmer Indonesia"
                class="brand-image opacity-75" />
            <span class="brand-text fw-light">Schlemmer Indonesia</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-header">DASHBOARD</li>
                <li class="nav-item">
                    <a href="<?= base_url() . 'dashboard' ?>" class="nav-link">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">MODULE</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-person-rolodex"></i>
                        <p>
                            HRIS
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Employee Mgmt.
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'employee' ?>" class="nav-link">
                                        <i class="nav-icon bi bi-arrow-return-right"></i>
                                        <p>
                                            List of Employee
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Job Data
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'job_data' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-return-right"></i>
                                        <p>
                                            Latest Job Data
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-buildings"></i>
                        <p>
                            Manufacturing
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-arrow-right-circle nav-icon"></i>
                                <p>
                                    SPK
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'spk' ?>" class="nav-link">
                                        <i class="bi bi-arrow-return-right nav-icon"></i>
                                        <p>
                                            List of SPK
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-boxes"></i>
                        <p>
                            R&D
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'project_setup' ?>" class="nav-link">
                                <i class="bi bi-arrow-right-circle nav-icon"></i>
                                <p>
                                    Project Setup
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">APP SETUP</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-person-workspace"></i>
                        <p>
                            HR Setup
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-diagram-3 nav-icon"></i>
                                <p>
                                    Organization
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'position' ?>" class="nav-link" onclick="loading()">
                                        <i class="bi bi-arrow-right-circle nav-icon"></i>
                                        <p>
                                            Position
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'department' ?>" class="nav-link" onclick="loading()">
                                        <i class="bi bi-arrow-right-circle nav-icon"></i>
                                        <p>
                                            Department
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'section' ?>" class="nav-link" onclick="loading()">
                                        <i class="bi bi-arrow-right-circle nav-icon"></i>
                                        <p>
                                            Section
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-person-gear nav-icon"></i>
                                <p>
                                    Employee Setup
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'employee_grade' ?>" class="nav-link" onclick="loading()">
                                        <i class="bi bi-arrow-right-circle nav-icon"></i>
                                        <p>
                                            Employee Grade
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'employee_category' ?>" class="nav-link" onclick="loading()">
                                        <i class="bi bi-arrow-right-circle nav-icon"></i>
                                        <p>
                                            Employee Category
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'employee_rank' ?>" class="nav-link" onclick="loading()">
                                        <i class="bi bi-arrow-right-circle nav-icon"></i>
                                        <p>
                                            Employee Rank
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'employee_class_nbhx' ?>" class="nav-link" onclick="loading()">
                                        <i class="bi bi-arrow-right-circle nav-icon"></i>
                                        <p>
                                            NBHX Employee Class
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'nbhx_position' ?>" class="nav-link" onclick="loading()">
                                        <i class="bi bi-arrow-right-circle nav-icon"></i>
                                        <p>
                                            NBHX Position
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'salary_rank' ?>" class="nav-link" onclick="loading()">
                                        <i class="bi bi-arrow-right-circle nav-icon"></i>
                                        <p>
                                            Salary Rank
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Common Data
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?= base_url() . 'family_relation' ?>" class="nav-link" onclick="loading()">
                                                <i class="nav-icon bi bi-arrow-bar-right"></i>
                                                <p>
                                                    Family Relation
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url() . 'education_degree' ?>" class="nav-link" onclick="loading()">
                                                <i class="nav-icon bi bi-arrow-bar-right"></i>
                                                <p>
                                                    Education Degree
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url() . 'family_occupation' ?>" class="nav-link" onclick="loading()">
                                                <i class="nav-icon bi bi-arrow-bar-right"></i>
                                                <p>
                                                    Family Occupation
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url() . 'employee_facility' ?>" class="nav-link" onclick="loading()">
                                                <i class="nav-icon bi bi-arrow-bar-right"></i>
                                                <p>
                                                    Employee Facility
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url() . 'uniform_type' ?>" class="nav-link" onclick="loading()">
                                                <i class="nav-icon bi bi-arrow-bar-right"></i>
                                                <p>
                                                    Uniform Type
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url() . 'uniform_size' ?>" class="nav-link" onclick="loading()">
                                                <i class="nav-icon bi bi-arrow-bar-right"></i>
                                                <p>
                                                    Uniform Size
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url() . 'shoes_size' ?>" class="nav-link" onclick="loading()">
                                                <i class="nav-icon bi bi-arrow-bar-right"></i>
                                                <p>
                                                    Shoes Size
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-briefcase"></i>
                                <p>
                                    Job Data Setup
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'job_data_action' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Job Data Action
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'job_data_reason' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Job Data Reason
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi- bi-clock"></i>
                                <p>
                                    Time & Labor Setup
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'absence_status' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Absence Status
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'overtime_setup' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Overtime Setup
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'shift_setup' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Shift Setup
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'special_leave_setup' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Special Leave Setup
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'schedulle_setup' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Schedulle Setup
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'period_setup' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Period Setup
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-buildings nav-icon"></i>
                        <p>
                            Mfg. Setup
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'workshop' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Workshop
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'tonnage' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Tonnage
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'material_category' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Material Category
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'equipment_type' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Equipment Type
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'uom' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of UoM
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'machine' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Machine List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'material' ?>" class="nav-link" onclick="loading();">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Material List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'team_leader' ?>" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Team Leader
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'group_leader' ?>" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Group Leader
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-columns-gap"></i>
                        <p>
                            RnD Setup
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'process_routes' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Process Routes
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'apqp_setup' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    APQP Setup
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class=" nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Docs. Flow Setup
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'document_stages' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-bar-right"></i>
                                        <p>
                                            Docs. Flow Stages
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'document_flow' ?>" class="nav-link" onclick="loading()">
                                        <i class="nav-icon bi bi-arrow-bar-right"></i>
                                        <p>
                                            Document Flow
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-rulers"></i>
                        <p>
                            Quality Setup
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-folder2-open"></i>
                        <p>
                            Basic Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'customer_category' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Customer Category
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'customer' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Customers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'supplier' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Supplier
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'factory' ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Factory
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'location' ?>" class="nav-link" onclick="laoding()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Factory Location
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-globe"></i>
                                <p>
                                    Region
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'country' ?>" class="nav-link">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Country
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'province' ?>" class="nav-link">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Province
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'city' ?>" class="nav-link">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            City
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url() . 'birth_place' ?>" class="nav-link">
                                        <i class="nav-icon bi bi-arrow-right-circle"></i>
                                        <p>
                                            Place of Birth
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-person-gear"></i>
                        <p>
                            User Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('users') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    User List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('user_role') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    User Role
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>
                            Application Setup
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('site-setting') ?>" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Site Setting
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url() . 'logout' ?>" class="nav-link">
                        <i class="nav-icon bi bi-box-arrow-left"></i>
                        <p>
                            Log Out
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>