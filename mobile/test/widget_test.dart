import 'package:flutter_test/flutter_test.dart';
import 'package:gis_limbah_b3_mobile/main.dart';

void main() {
  testWidgets('App smoke test - GisLimbahB3App builds', (WidgetTester tester) async {
    await tester.pumpWidget(const GisLimbahB3App());
    expect(find.byType(GisLimbahB3App), findsOneWidget);
  });
}
