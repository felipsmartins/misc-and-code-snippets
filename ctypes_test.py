import os
import sys
import ctypes

drive = os.getenv("SytemDrive")
freebytes_cPointer = ctypes.c_ulonglong()
ctypes.windll.kernel32.GetDiskFreeSpaceExW(ctypes.c_wchar_p(drive), None, None, ctypes.pointer(freebytes_cPointer))

"""
def get_free_space(folder):
    'Return folder/drive free space (in bytes)'
    
        if platform.system() == 'Windows':
        free_bytes = ctypes.c_ulonglong(0)
        ctypes.windll.kernel32.GetDiskFreeSpaceExW(ctypes.c_wchar_p(folder), None, None, ctypes.pointer(free_bytes))
        return free_bytes.value
    else:
        return os.statvfs(folder).f_bfree
"""

def disk_c():
    import os
    import ctypes
    
    drive = unicode(os.getenv("SystemDrive"))
    freeuser = ctypes.c_int64()
    total = ctypes.c_int64()
    free = ctypes.c_int64()
    ctypes.windll.kernel32.GetDiskFreeSpaceExW(drive, 
                                    ctypes.byref(freeuser), 
                                    ctypes.byref(total), 
                                    ctypes.byref(free))
    
    return {'freeuser': freeuser, 'total': total, 'free': free}



for key in disk_c().keys():
    print "%s => %s ================> %.1f GB" % (key, disk_c().get(key).value, (disk_c().get(key).value) / 1024**3)