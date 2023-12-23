theatre, num_row, num_col, seat_row, seat_col = input('Ticket Details: ').split()

ascii_start = ord('A')

num_row_int  = int(num_row)
num_col_int  = int(num_col)
seat_row_int = ord(seat_row) - ascii_start + 1
seat_col_int = int(seat_col)

if seat_row_int > num_row_int or seat_col_int > num_col_int :

    print('There is not seat in theatre')
    
    exit()

print()

if seat_row_int > num_row_int - 4 :
    price = 220
else :
    price = 200

print('Theatre',theatre)
print('Price =', price)
print()

for j in range(num_col_int) :

    if j == 0 :
        print('%d' %(j+1), end = ' ')
    else :
        print('%2d' %(j+1), end = ' ')

print()

for i in range(num_row_int-1,-1,-1) :

    for j in range(num_col_int) :

        if i == seat_row_int - 1 and j == seat_col_int - 1:
            print('X  ', end = '')
        else :
            print('O  ', end = '')

    print(chr(ascii_start + i), end = '')

    print()

print('-'*3*num_col_int+'-')
print('Screen')
print('-'*3*num_col_int+'-')