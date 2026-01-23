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
                <li class="nav-header">TRANSACTION</li>
                <li class="nav-item">
                    <a href="<?= base_url() . 'document' ?>" class="nav-link" onclick="loading();">
                        <i class="nav-icon bi bi-folder2-open"></i>
                        <p>
                            List of Project
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-vector-pen"></i>
                        <p>
                            Approval
                        </p>
                    </a>
                </li>
                <li class="nav-header">MASTER DATA</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam"></i>
                        <p>
                            Common Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('customer_category') ?> " class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Customer Category
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('customer') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Customers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('satuan') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of UoM
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('routes') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Routes
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('material_category') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Material Category List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('workshop') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Workshop
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('material') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Material
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>
                            APQP Setup
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('apqp_level') ?>" class="nav-link" onclick="loading()">
                                <i class="bi bi-arrow-right-circle"></i>
                                <p>
                                    APQP Setup
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-bezier2 nav-icon"></i>
                        <p>
                            Project Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'project'  ?>" class="nav-link">
                                <i class="bi bi-arrow-right-circle"></i>
                                <p>
                                    Project Setup
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-data"></i>
                        <p>
                            Document Flow
                        </p>
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'document-flow' ?>" class="nav-link">
                                <i class="bi bi-arrow-right-circle"></i>
                                <p>
                                    Setup Document Flow
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
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Leader
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Group Leader
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Asst. Group Leader
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
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Supplier
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url() . 'location' ?>" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Factory Location
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Unit of Measure (UoM)
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