<?php

namespace App\Enums;

enum PermissionEnum: string
{
    /** AUTH */
    case AUTH_UPDATE = 'AUTH_UPDATE';

    /** USER */
    case USER_INDEX = 'USER_INDEX';
    case USER_SHOW = 'USER_SHOW';
    case USER_STORE = 'USER_STORE';
    case USER_UPDATE = 'USER_UPDATE';
    case USER_DESTROY = 'USER_DESTROY';

    /** ITEM CATEGORY */
    case ITEM_CATEGORY_STORE = 'ITEM_CATEGORY_STORE';
    case ITEM_CATEGORY_UPDATE = 'ITEM_CATEGORY_UPDATE';
    case ITEM_CATEGORY_SHOW = 'ITEM_CATEGORY_SHOW';
    case ITEM_CATEGORY_INDEX = 'ITEM_CATEGORY_INDEX';
    case ITEM_CATEGORY_DESTROY = 'ITEM_CATEGORY_DESTROY';

    /** GENERAL ITEM */
    case GENERAL_ITEM_STORE = 'GENERAL_ITEM_STORE';
    case GENERAL_ITEM_UPDATE = 'GENERAL_ITEM_UPDATE';
    case GENERAL_ITEM_DESTROY = 'GENERAL_ITEM_DESTROY';
    case GENERAL_ITEM_INDEX = 'GENERAL_ITEM_INDEX';
    case GENERAL_ITEM_SHOW = 'GENERAL_ITEM_SHOW';

    /** CUSTOMER */
    case CUSTOMER_STORE = 'CUSTOMER_STORE';
    case CUSTOMER_UPDATE = 'CUSTOMER_UPDATE';
    case CUSTOMER_DESTROY = 'CUSTOMER_DESTROY';
    case CUSTOMER_INDEX = 'CUSTOMER_INDEX';
    case CUSTOMER_SHOW = 'CUSTOMER_SHOW';

    /** CUSTOMER ADDRESS */
    case CUSTOMER_ADDRESS_STORE = 'CUSTOMER_ADDRESS_STORE';
    case CUSTOMER_ADDRESS_UPDATE = 'CUSTOMER_ADDRESS_UPDATE';
    case CUSTOMER_ADDRESS_DESTROY = 'CUSTOMER_ADDRESS_DESTROY';
    case CUSTOMER_ADDRESS_INDEX = 'CUSTOMER_ADDRESS_INDEX';
    case CUSTOMER_ADDRESS_SHOW = 'CUSTOMER_ADDRESS_SHOW';

    /** Tag */
    case TAG_STORE = 'TAG_STORE';
    case TAG_SHOW = 'TAG_SHOW';
    case TAG_UPDATE = 'TAG_UPDATE';
    case TAG_INDEX = 'TAG_INDEX';
    case TAG_DESTROY = 'TAG_DESTROY';

    /** Business */
    case BUSINESS_STORE = 'BUSINESS_STORE';
    case BUSINESS_SHOW = 'BUSINESS_SHOW';
    case BUSINESS_UPDATE = 'BUSINESS_UPDATE';
    case BUSINESS_INDEX = 'BUSINESS_INDEX';
    case BUSINESS_DESTROY = 'BUSINESS_DESTROY';

    /** Contact Person */
    case CONTACT_PERSON_STORE = 'CONTACT_PERSON_STORE';
    case CONTACT_PERSON_SHOW = 'CONTACT_PERSON_SHOW';
    case CONTACT_PERSON_UPDATE = 'CONTACT_PERSON_UPDATE';
    case CONTACT_PERSON_INDEX = 'CONTACT_PERSON_INDEX';
    case CONTACT_PERSON_DESTROY = 'CONTACT_PERSON_DESTROY';

    /** Employee */
    case EMPLOYEE_STORE = 'EMPLOYEE_STORE';
    case EMPLOYEE_SHOW = 'EMPLOYEE_SHOW';
    case EMPLOYEE_UPDATE = 'EMPLOYEE_UPDATE';
    case EMPLOYEE_INDEX = 'EMPLOYEE_INDEX';
    case EMPLOYEE_DESTROY = 'EMPLOYEE_DESTROY';

    /** FILE */
    case FILE_STORE = 'FILE_STORE';
    case FILE_SHOW = 'FILE_SHOW';
    case FILE_INDEX = 'FILE_INDEX';
    case FILE_DESTROY = 'FILE_DESTROY';

    /** PURCHASE */
    case PURHCASE_STORE = 'PURCHASE_STORE';
    case PURHCASE_INDEX = 'PURHCASE_INDEX';
    case PURHCASE_SHOW = 'PURHCASE_SHOW';

    /** ROLE */
    case ROLE_STORE = 'ROLE_STORE';
    case ROLE_SHOW = 'ROLE_SHOW';
    case ROLE_UPDATE = 'ROLE_UPDATE';
    case ROLE_INDEX = 'ROLE_INDEX';
    case ROLE_DESTROY = 'ROLE_DESTROY';

    /** PERMISSION */
    case PERMISSION_INDEX = 'PERMISSION_INDEX';
    case PERMISSION_SHOW = 'PERMISSION_SHOW';
}