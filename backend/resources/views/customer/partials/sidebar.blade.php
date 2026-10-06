<div class="account-sidebar">
    <div class="account-menu">
        <ul>
            <li>
                <a href="{{ route('customer.account.dashboard') }}" class="{{ request()->routeIs('customer.account.dashboard') ? 'active' : '' }}">
                    <i class="lnr lnr-home"></i>
                    <span>{{ Session::get('locale') === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customer.account.orders') }}" class="{{ request()->routeIs('customer.account.orders') || request()->routeIs('customer.account.order.details') ? 'active' : '' }}">
                    <i class="lnr lnr-cart"></i>
                    <span>{{ Session::get('locale') === 'ar' ? 'طلباتي' : 'My Orders' }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customer.account.quote_requests') }}" class="{{ request()->routeIs('customer.account.quote_requests') || request()->routeIs('customer.account.quote_requests.details') ? 'active' : '' }}">
                    <i class="lnr lnr-file-empty"></i>
                    <span>{{ Session::get('locale') === 'ar' ? 'طلبات عروض الأسعار' : 'Quote Requests' }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customer.account.wishlist') }}" class="{{ request()->routeIs('customer.account.wishlist') ? 'active' : '' }}">
                    <i class="lnr lnr-heart"></i>
                    <span>{{ Session::get('locale') === 'ar' ? 'قائمة الأمنيات' : 'Wishlist' }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customer.account.profile') }}" class="{{ request()->routeIs('customer.account.profile') ? 'active' : '' }}">
                    <i class="lnr lnr-user"></i>
                    <span>{{ Session::get('locale') === 'ar' ? 'الملف الشخصي' : 'Profile' }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customer.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="lnr lnr-exit"></i>
                    <span>{{ Session::get('locale') === 'ar' ? 'تسجيل الخروج' : 'Logout' }}</span>
                </a>
                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>

<style>
.account-sidebar {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.account-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.account-menu li {
    margin-bottom: 10px;
}

.account-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 15px;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s;
}

.account-menu a:hover,
.account-menu a.active {
    background: #007bff;
    color: #fff;
}

.account-menu i {
    font-size: 18px;
    width: 20px;
}
</style>
