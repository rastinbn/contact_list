import sys
from timezonefinder import TimezoneFinder
import pytz
from datetime import datetime
import json

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print(json.dumps({"error": "Usage: python get_timezone.py <latitude> <longitude>"}))
        sys.exit(1)

    try:
        latitude = float(sys.argv[1])
        longitude = float(sys.argv[2])
    except ValueError:
        print(json.dumps({"error": "Invalid latitude or longitude."}))
        sys.exit(1)

    tf = TimezoneFinder()
    timezone_str = tf.timezone_at(lng=longitude, lat=latitude)

    if timezone_str:
        try:
            tz = pytz.timezone(timezone_str)
            now = datetime.now(tz)
            current_hour = now.strftime("%H")
            current_minute = now.strftime("%M")

            print(json.dumps({
                "timezone": timezone_str,
                "hour": current_hour,
                "minute": current_minute
            }))
        except pytz.UnknownTimeZoneError:
            print(json.dumps({"error": f"Unknown timezone: {timezone_str}"}))
            sys.exit(1)
    else:
        print(json.dumps({"error": "Unknown timezone."}))
        sys.exit(1)
