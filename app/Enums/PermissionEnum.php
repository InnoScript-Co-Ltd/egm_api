<?php

namespace App\Enums;

enum PermissionEnum: string
{
    /** Agent */
    case AGENT_INDEX = 'AGENT_INDEX';
    case AGENT_SHOW = 'AGENT_SHOW';
    case AGENT_STORE = 'AGENT_STORE';
    case AGENT_UPDATE = 'AGENT_UPDATE';
    case AGENT_DESTROY = 'AGENT_DESTROY';
    case AGENT_EXPORT = 'AGENT_EXPORT';

    /** Sub Agent */
    case SUB_AGENT_INDEX = 'SUB_AGENT_INDEX';
    case SUB_AGENT_SHOW = 'SUB_AGENT_SHOW';
    case SUB_AGENT_STORE = 'SUB_AGENT_STORE';
    case SUB_AGENT_UPDATE = 'SUB_AGENT_UPDATE';
    case SUB_AGENT_DESTROY = 'SUB_AGENT_DESTROY';
    case SUB_AGENT_EXPORT = 'SUB_AGENT_EXPORT';

    /** Agent Bank Account */
    case AGENT_BANK_ACCOUNT_SHOW = 'AGENT_BANK_ACCOUNT_SHOW';
    case AGENT_BANK_ACCOUNT_INDEX = 'AGENT_BANK_ACCOUNT_INDEX';
    case AGENT_BANK_ACCOUNT_STORE = 'AGENT_BANK_ACCOUNT_STORE';
    case AGENT_BANK_ACCOUNT_UPDATE = 'AGENT_BANK_ACCOUNT_UPDATE';
    case AGENT_BANK_ACCOUNT_DESTROY = 'AGENT_BANK_ACCOUNT_DESTROY';
    case AGENT_BANK_ACCOUNT_EXPORT = 'AGENT_BANK_ACCOUNT_EXPORT';

    /** Merchant Bank Account */
    case MERCHANT_BANK_ACCOUNT_INDEX = 'MERCHANT_BANK_ACCOUNT_INDEX';
    case MERCHANT_BANK_ACCOUNT_SHOW = 'MERCHANT_BANK_ACCOUNT_SHOW';
    case MERCHANT_BANK_ACCOUNT_STORE = 'MERCHANT_BANK_ACCOUNT_STORE';
    case MERCHANT_BANK_ACCOUNT_UPDATE = 'MERCHANT_BANK_ACCOUNT_UPDATE';
    case MERCHANT_BANK_ACCOUNT_DESTROY = 'MERCHANT_BANK_ACCOUNT_DESTROY';
    case MERCHANT_BANK_ACCOUNT_EXPORT = 'MERCHANT_BANK_ACCOUNT_EXPORT';

    /** PARTNER */
    case PARTNER_INDEX = 'PARTNER_INDEX';
    case PARTNER_SHOW = 'PARTNER_SHOW';
    case PARTNER_STORE = 'PARTNER_STORE';
    case PARTNER_UPDATE = 'PARTNER_UPDATE';
    case PARTNER_DESTROY = 'PARTNER_DESTROY';
    case PARTNER_EXPORT = 'PARTNER_EXPORT';
    case PARTNER_GENERATE_PASSWORD = 'PARTNER_GENERATE_PASSWORD';
    case PARTNER_APPROVE_ACCOUNT = 'PARTNER_APPROVE_ACCOUNT';
    case PARTNER_APPROVE_KYC = 'PARTNER_APPROVE_KYC';

    /** Package */
    case PACKAGE_INDEX = 'PACKAGE_INDEX';
    case PACKAGE_SHOW = 'PACKAGE_SHOW';
    case PACKAGE_STORE = 'PACKAGE_STORE';
    case PACKAGE_UPDATE = 'PACKAGE_UPDATE';
    case PACKAGE_DESTROY = 'PACKAGE_DESTROY';
    case PACKAGE_EXPORT = 'PACKAGE_EXPORT';

    /** Transaction Permissions */
    case TRANSACTION_INDEX = 'TRANSACTION_INDEX';
    case TRANSACTION_SHOW = 'TRANSACTION_SHOW';
    case TRANSACTION_UPDATE = 'TRANSACTION_UPDATE';
    case TRANSACTION_DESTROY = 'TRANSACTION_DESTROY';
    case TRANSACTION_STORE = 'TRANSACTION_STORE';
    case TRANSACTION_MAKE_PAYMENT = 'TRANSACTION_MAKE_PAYMENT';
    case TRANSACTION_REJECT = 'TRANSACTION_REJECT';

    /** Deposit Permissions */
    case DEPOSIT_INDEX = 'DEPOSIT_INDEX';
    case DEPOSIT_SHOW = 'DEPOSIT_SHOW';
    case DEPOSIT_UPDATE = 'DEPOSIT_UPDATE';
    case DEPOSIT_DESTROY = 'DEPOSIT_DESTROY';
    case DEPOSIT_STORE = 'DEPOSIT_STORE';

    /** AUTH */
    case AUTH_UPDATE = 'AUTH_UPDATE';

    /** USER */
    case USER_INDEX = 'USER_INDEX';
    case USER_SHOW = 'USER_SHOW';
    case USER_STORE = 'USER_STORE';
    case USER_UPDATE = 'USER_UPDATE';
    case USER_DESTROY = 'USER_DESTROY';
    case USER_EXPORT = 'USER_EXPORT';

    /** ITEM */
    case ITEM_STORE = 'ITEM_STORE';
    case ITEM_UPDATE = 'ITEM_UPDATE';
    case ITEM_SHOW = 'ITEM_SHOW';
    case ITEM_INDEX = 'ITEM_INDEX';
    case ITEM_DESTROY = 'ITEM_DESTROY';

    /** CATEGORY */
    case CATEGORY_STORE = 'CATEGORY_STORE';
    case CATEGORY_UPDATE = 'CATEGORY_UPDATE';
    case CATEGORY_DESTROY = 'CATEGORY_DESTROY';
    case CATEGORY_INDEX = 'CATEGORY_INDEX';
    case CATEGORY_SHOW = 'CATEGORY_SHOW';

    /** ADMIN */
    case ADMIN_STORE = 'ADMIN_STORE';
    case ADMIN_UPDATE = 'ADMIN_UPDATE';
    case ADMIN_DESTROY = 'ADMIN_DESTROY';
    case ADMIN_INDEX = 'ADMIN_INDEX';
    case ADMIN_SHOW = 'ADMIN_SHOW';

    /** DELIVERY ADDRESS */
    case DELIVERY_ADDRESS_STORE = 'DELIVERY_ADDRESS_STORE';
    case DELIVERY_ADDRESS_UPDATE = 'DELIVERY_ADDRESS_UPDATE';
    case DELIVERY_ADDRESS_DESTROY = 'DELIVERY_ADDRESS_DESTROY';
    case DELIVERY_ADDRESS_INDEX = 'DELIVERY_ADDRESS_INDEX';
    case DELIVERY_ADDRESS_SHOW = 'DELIVERY_ADDRESS_SHOW';

    /** FAQ */
    case FAQ_STORE = 'FAQ_STORE';
    case FAQ_SHOW = 'FAQ_SHOW';
    case FAQ_UPDATE = 'FAQ_UPDATE';
    case FAQ_INDEX = 'FAQ_INDEX';
    case FAQ_DESTROY = 'FAQ_DESTROY';

    /** ORDER */
    case ORDER_STORE = 'ORDER_STORE';
    case ORDER_SHOW = 'ORDER_SHOW';
    case ORDER_UPDATE = 'ORDER_UPDATE';
    case ORDER_INDEX = 'ORDER_INDEX';
    case ORDER_DESTROY = 'ORDER_DESTROY';

    case INVOICE_STORE = 'INVOICE_STORE';
    case INVOICE_SHOW = 'INVOICE_SHOW';
    case INVOICE_UPDATE = 'INVOICE_UPDATE';
    case INVOICE_INDEX = 'INVOICE_INDEX';
    case INVOIVE_DESTROY = 'INVOICE_DESTROY';

    /** POINT */
    case POINT_STORE = 'POINT_STORE';
    case POINT_SHOW = 'POINT_SHOW';
    case POINT_UPDATE = 'POINT_UPDATE';
    case POINT_INDEX = 'POINT_INDEX';
    case POINT_DESTROY = 'POINT_DESTROY';

    /** BANNER */
    case BANNER_STORE = 'BANNER_STORE';
    case BANNER_SHOW = 'BANNER_SHOW';
    case BANNER_UPDATE = 'BANNER_UPDATE';
    case BANNER_INDEX = 'BANNER_INDEX';
    case BANNER_DESTROY = 'BANNER_DESTROY';

    /** Promotion */
    case PROMOTION_STORE = 'PROMOTION_STORE';
    case PROMOTION_SHOW = 'PROMOTION_SHOW';
    case PROMOTION_UPDATE = 'PROMOTION_UPDATE';
    case PROMOTION_INDEX = 'PROMOTION_INDEX';
    case PROMOTION_DESTROY = 'PROMOTION_DESTROY';

    /** Promotion Item */
    case PROMOTION_ITEM_STORE = 'PROMOTION_ITEM_STORE';
    case PROMOTION_ITEM_SHOW = 'PROMOTION_ITEM_SHOW';
    case PROMOTION_ITEM_UPDATE = 'PROMOTION_ITEM_UPDATE';
    case PROMOTION_ITEM_INDEX = 'PROMOTION_ITEM_INDEX';
    case PROMOTION_ITEM_DESTROY = 'PROMOTION_ITEM_DESTROY';

    /** Region */
    case REGION_STORE = 'REGION_STORE';
    case REGION_SHOW = 'REGION_SHOW';
    case REGION_UPDATE = 'REGION_UPDATE';
    case REGION_INDEX = 'REGION_INDEX';
    case REGION_DESTROY = 'REGION_DESTROY';

    /** Shop */
    case SHOP_STORE = 'SHOP_STORE';
    case SHOP_SHOW = 'SHOP_SHOW';
    case SHOP_UPDATE = 'SHOP_UPDATE';
    case SHOP_INDEX = 'SHOP_INDEX';
    case SHOP_DESTROY = 'SHOP_DESTROY';

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
    case ROLE_PERMISSION_REMOVE = 'ROLE_PERMISSION_REMOVE';
    case ROLE_ASSIGN = 'ROLE_ASSIGN';

    /** Member */
    case MEMBER_STORE = 'MEMBER_STORE';
    case MEMBER_SHOW = 'MEMBER_SHOW';
    case MEMBER_ID_NEXT = 'MEMBER_ID_NEXT';
    case MEMBER_INDEX = 'MEMBER_INDEX';
    case MEMBER_DESTROY = 'MEMBER_DESTROY';
    case MEMBER_UPDATE = 'MEMBER_UPDATE';
    case MEMBER_SETTING = 'MEMBER_SETTING';

    /** Member Discount */
    case MEMBER_DISCOUNT_STORE = 'MEMBER_DISCOUNT_STORE';
    case MEMBER_DISCOUNT_SHOW = 'MEMBER_DISCOUNT_SHOW';
    case MEMBER_DISCOUNT_INDEX = 'MEMBER_DISCOUNT_INDEX';
    case MEMBER_DISCOUNT_DESTROY = 'MEMBER_DISCOUNT_DESTROY';
    case MEMBER_DISCOUNT_UPDATE = 'MEMBER_DISCOUNT_UPDATE';

    /** Member Card */
    case MEMBER_CARD_STORE = 'MEMBER_CARD_STORE';
    case MEMBER_CARD_SHOW = 'MEMBER_CARD_SHOW';
    case MEMBER_CARD_INDEX = 'MEMBER_CARD_INDEX';
    case MEMBER_CARD_DESTROY = 'MEMBER_CARD_DESTROY';
    case MEMBER_CARD_UPDATE = 'MEMBER_CARD_UPDATE';

    /** Member Order */
    case MEMBER_ORDER_INDEX = 'MEMBER_ORDER_INDEX';
    case MEMBER_ORDER_SHOW = 'MEMBER_ORDER_SHOW';

    /** PERMISSION */
    case PERMISSION_INDEX = 'PERMISSION_INDEX';
    case PERMISSION_SHOW = 'PERMISSION_SHOW';

    /** COUNTRY */
    case COUNTRY_INDEX = 'COUNTRY_INDEX';
    case COUNTRY_UPDATE = 'COUNTRY_UPDATE';
    case COUNTRY_SHOW = 'COUNTRY_SHOW';
    case COUNTRY_DESTROY = 'COUNTRY_DESTROY';
    case COUNTRY_STORE = 'COUNTRY_STORE';

    /** REGION AND STATE */
    case REGION_AND_STATE_INDEX = 'REGION_AND_STATE_INDEX';
    case REGION_AND_STATE_UPDATE = 'REGION_AND_STATE_UPDATE';
    case REGION_AND_STATE_SHOW = 'REGION_AND_STATE_SHOW';
    case REGION_AND_STATE_DESTROY = 'REGION_AND_STATE_DESTROY';
    case REGION_AND_STATE_STORE = 'REGION_AND_STATE_STORE';

    /** CITY */
    case CITY_INDEX = 'CITY_INDEX';
    case CITY_UPDATE = 'CITY_UPDATE';
    case CITY_SHOW = 'CITY_SHOW';
    case CITY_DESTROY = 'CITY_DESTROY';
    case CITY_STORE = 'CITY_STORE';

    /** TOWNSHIP */
    case TOWNSHIP_INDEX = 'TOWNSHIP_INDEX';
    case TOWNSHIP_UPDATE = 'TOWNSHIP_UPDATE';
    case TOWNSHIP_SHOW = 'TOWNSHIP_SHOW';
    case TOWNSHIP_DESTROY = 'TOWNSHIP_DESTROY';
    case TOWNSHIP_STORE = 'TOWNSHIP_STORE';

    /** EMAIL_CONTENT */
    case EMAIL_CONTENT_STORE = 'EMAIL_CONTENT_STORE';
    case EMAIL_CONTENT_INDEX = 'EMAIL_CONTENT_INDEX';
    case EMAIL_CONTENT_SHOW = 'EMAIL_CONTENT_SHOW';
    case EMAIL_CONTENT_UPDATE = 'EMAIL_CONTENT_UPDATE';
    case EMAIL_CONTENT_DESTROY = 'EMAIL_CONTENT_DESTROY';

    /** ARTICLE */
    case ARTICLE_TYPE_INDEX = 'ARTICLE_TYPE_INDEX';
    case ARTICLE_TYPE_STORE = 'ARTICLE_TYPE_STORE';
    case ARTICLE_TYPE_SHOW = 'ARTICLE_TYPE_SHOW';
    case ARTICLE_TYPE_UPDATE = 'ARTICLE_TYPE_UPDATE';
    case ARTICLE_TYPE_DESTROY = 'ARTICLE_TYPE_DESTROY';

    /** ARTICLE */
    case ARTICLE_INDEX = 'ARTICLE_INDEX';
    case ARTICLE_STORE = 'ARTICLE_STORE';
    case ARTICLE_SHOW = 'ARTICLE_SHOW';
    case ARTICLE_UPDATE = 'ARTICLE_UPDATE';
    case ARTICLE_DESTROY = 'ARTICLE_DESTROY';

    /** COMMENT */
    case COMMENT_INDEX = 'COMMENT_INDEX';
    case COMMENT_STORE = 'COMMENT_STORE';
    case COMMENT_SHOW = 'COMMENT_SHOW';
    case COMMENT_UPDATE = 'COMMENT_UPDATE';
    case COMMENT_DESTROY = 'COMMENT_DESTROY';

    /** ARTICLE LIKE */
    case ARTICLE_LIKE_INDEX = 'ARTICLE_LIKE_INDEX';
    case ARTICLE_LIKE_STORE = 'ARTICLE_LIKE_STORE';
    case ARTICLE_LIKE_SHOW = 'ARTICLE_LIKE_SHOW';
    case ARTICLE_LIKE_DESTROY = 'ARTICLE_LIKE_DESTROY';

    /** BANK ACCOUNT TYPE */
    case BANK_TYPE_INDEX = 'BANK_TYPE_INDEX';
    case BANK_TYPE_STORE = 'BANK_TYPE_STORE';
    case BANK_TYPE_SHOW = 'BANK_TYPE_SHOW';
    case BANK_TYPE_DESTROY = 'BANK_TYPE_DESTROY';
    case BANK_TYPE_UPDATE = 'BANK_TYPE_UPDATE';

    /** REPAYMENT  */
    case REPAYMENT_INDEX = 'REPAYMENT_INDEX';
    case REPAYMENT_STORE = 'REPAYMENT_STORE';
    case REPAYMENT_SHOW = 'REPAYMENT_SHOW';
    case REPAYMENT_DESTROY = 'REPAYMENT_DESTROY';
    case REPAYMENT_UPDATE = 'REPAYMENT_UPDATE';

    /** BONUS POINT  */
    case BONUS_POINT_INDEX = 'BONUS_POINT_INDEX';
    case BONUS_POINT_STORE = 'BONUS_POINT_STORE';
    case BONUS_POINT_SHOW = 'BONUS_POINT_SHOW';
    case BONUS_POINT_DESTROY = 'BONUS_POINT_DESTROY';
    case BONUS_POINT_UPDATE = 'BONUS_POINT_UPDATE';

    /** USDT ADDRESS  */
    case USDT_ADDRESS_INDEX = 'USDT_ADDRESS_INDEX';
    case USDT_ADDRESS_STORE = 'USDT_ADDRESS_STORE';
    case USDT_ADDRESS_SHOW = 'USDT_ADDRESS_SHOW';
    case USDT_ADDRESS_DESTROY = 'USDT_ADDRESS_DESTROY';
    case USDT_ADDRESS_UPDATE = 'USDT_ADDRESS_UPDATE';

    /** HISTORY  */
    case HISTORY_SHOW = 'HISTORY_SHOW';
    case HISTORY_REPAYMENT_INDEX = 'HISTORY_REPAYMENT_INDEX';
    case HISTORY_TRANSACTION_INDEX = 'HISTORY_TRANSACTION_INDEX';
    case HISTORY_WITHDRAW_INDEX = 'HISTORY_WITHDRAW_INDEX';
}
