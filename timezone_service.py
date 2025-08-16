from flask import Flask, request, jsonify
from timezonefinder import TimezoneFinder
import pytz
from datetime import datetime

app = Flask(__name__)
tf = TimezoneFinder()

@app.route('/get_timezone', methods=['GET'])
def get_timezone():
    latitude = request.args.get('latitude', type=float)
    longitude = request.args.get('longitude', type=float)

    if latitude is None or longitude is None:
        return jsonify({"error": "Missing latitude or longitude"}), 400

    timezone_str = tf.timezone_at(lng=longitude, lat=latitude)

    if timezone_str:
        try:
            tz = pytz.timezone(timezone_str)
            now = datetime.now(tz)
            current_hour = now.strftime("%H")
            current_minute = now.strftime("%M")

            return jsonify({
                "timezone": timezone_str,
                "hour": current_hour,
                "minute": current_minute
            })
        except pytz.UnknownTimeZoneError:
            return jsonify({"error": f"Unknown timezone: {timezone_str}"}), 500
    else:
        return jsonify({"error": "Unknown timezone for the given coordinates."}), 404

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
