import sys
from timezonefinder import TimezoneFinder

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("Usage: python get_timezone.py <latitude> <longitude>", file=sys.stderr)
        sys.exit(1)

    try:
        latitude = float(sys.argv[1])
        longitude = float(sys.argv[2])
    except ValueError:
        print("Invalid latitude or longitude.", file=sys.stderr)
        sys.exit(1)

    tf = TimezoneFinder()
    timezone_str = tf.timezone_at(lng=longitude, lat=latitude)

    if timezone_str:
        print(timezone_str)
    else:
        print("Unknown timezone.", file=sys.stderr)
