str1 = input("Enter the number in the list: ")
str2 = str1.split()
lst = []
for i in range(0,len(str2)):
    lst.append(int(str2[i]))


result = []
for i in lst:
   if i < 0:
       if i%2==0:
           result.append(i)
result.sort()
# printing
print ("List of negative even number: " + str(result))
print("The original list: " + str(lst))