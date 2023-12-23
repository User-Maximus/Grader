inp = input('Enter a sequence of integers: ').split()
smallest1 = None
smallest2 = None

for i in inp:
    i = int(i)
    if i % 3 != 0:
        continue
    if smallest2 is None and smallest1 is not None:
        smallest2 = i
    if smallest1 is None:
        smallest1 = i
    if smallest2 is not None and i < smallest2:
        smallest2 = i
    if smallest1 is not None and i < smallest1:
        smallest2 = smallest1
        smallest1 = i

if smallest1 is not None and smallest2 is not None:
    print('The smallest integer divisible by 3 is', smallest1)
    print('The second-smallest integer divisible by 3 is', smallest2)
else:
    print('Insufficient input data')
