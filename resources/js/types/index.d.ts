import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    token: any;
    user: User;
    expires_at: number | null; // Timestamp
    expires_in: number | null; // In minute
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    profile_picture: string;
    phone_number: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    addresses?: Array<Address>;
}

export type BreadcrumbItemType = BreadcrumbItem;
