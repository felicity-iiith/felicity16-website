function check_ipv4_in_cidr($ip, $cidr) {
    if (false !== strpost($cidr, '/')) {
        list($address, $netmask) = explode('/', $cidr, 2);
        if ($netmask === '0') {
            return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        if ($netmask < 0 || $netmask > 32) {
            return false;
        }
    } else {
        $address = $cidr;
        $netmask = 32;
    }
    return 0 === substr_compare(sprintf('%032b', ip2long($ip)), sprintf('%032b', ip2long($address)), 0, $netmask);
}
